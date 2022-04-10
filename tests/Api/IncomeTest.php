<?php

declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use Shoman4eg\Nalog\DTO;
use Shoman4eg\Nalog\Enum\CancelCommentType;
use Shoman4eg\Nalog\Enum\IncomeType;
use Shoman4eg\Nalog\Enum\PaymentType;
use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 * @coversNothing
 */
class IncomeTest extends ApiTestCase
{
    public function clientDataProvider(): iterable
    {
        yield [new DTO\IncomeClient()];
        yield [new DTO\IncomeClient(null, 'testClient', IncomeType::LEGAL_ENTITY, '1234567890')];
        yield [new DTO\IncomeClient(null, null, IncomeType::INDIVIDUAL, null)];
        yield [new DTO\IncomeClient('phone', 'testClient', IncomeType::FOREIGN_AGENCY)];
    }

    /**
     * @dataProvider clientDataProvider
     *
     * @throws \JsonException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Shoman4eg\Nalog\Exception\DomainException
     */
    public function testCreate(?DTO\IncomeClient $client): void
    {
        $receiptId = 'randomReceiptId';
        $this->appendSuccessJson(['approvedReceiptUuid' => $receiptId]);
        $response = $this->client->income()->create('name', 100, 1, null, $client);

        self::assertSame($receiptId, $response->getApprovedReceiptUuid());
    }

    /**
     * @throws \JsonException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Shoman4eg\Nalog\Exception\DomainException
     */
    public function testCreateLegalEntityClient(): void
    {
        $receiptId = 'randomReceiptId';
        $this->appendSuccessJson(['approvedReceiptUuid' => $receiptId]);
        $client = new DTO\IncomeClient(null, 'testClient', IncomeType::LEGAL_ENTITY, '1234567890');
        $response = $this->client->income()->create('name', 100, 1, null, $client);

        self::assertSame($receiptId, $response->getApprovedReceiptUuid());
    }

    public function validationCreateDataProvider(): iterable
    {
        yield ['', 100, 1, null, 'Name cannot be empty'];
        yield ['name', '', 1, null, 'Amount must be int or float'];
        yield ['name', -1, 1, null, 'Amount must be greater than 0'];
        yield ['name', 1, 0, null, 'Quantity cannot be empty'];
        yield ['name', 1, 'zero', null, 'Quantity must be int or float'];
        yield ['name', 1, 1,  new DTO\IncomeClient(null, '', IncomeType::LEGAL_ENTITY, ''), 'Client INN cannot be empty'];
        yield ['name', 1, 1, new DTO\IncomeClient(null, '', IncomeType::LEGAL_ENTITY, 'aaaa'), 'Client INN must contain only numbers'];
        yield ['name', 1, 1, new DTO\IncomeClient(null, '', IncomeType::LEGAL_ENTITY, '1234'), 'Client INN length must been 10 or 12'];
        yield ['name', 1, 1, new DTO\IncomeClient(null, '', IncomeType::LEGAL_ENTITY, '1234567890'), 'Client DisplayName cannot be empty'];
    }

    /**
     * @dataProvider validationCreateDataProvider
     *
     * @param mixed $amount
     * @param mixed $quantity
     *
     * @throws \JsonException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Shoman4eg\Nalog\Exception\DomainException
     */
    public function testCreateValidation(string $name, $amount, $quantity, ?DTO\IncomeClient $client, string $message): void
    {
        $this->expectExceptionMessage($message);
        $this->client->income()->create($name, $amount, $quantity, null, $client);
    }

    public function cancellationDataProvider(): iterable
    {
        yield ['12345678', CancelCommentType::CANCEL];
        yield ['12345678', CancelCommentType::REFUND];
    }

    /**
     * @dataProvider cancellationDataProvider
     *
     * @throws \JsonException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Shoman4eg\Nalog\Exception\DomainException
     */
    public function testCancel(string $receiptId, string $comment): void
    {
        $this->appendSuccessJson([
            'incomeInfo' => [
                'approvedReceiptUuid' => $receiptId,
                'name' => '',
                'operationTime' => '',
                'requestTime' => '',
                'paymentType' => PaymentType::CASH,
                'partnerCode' => '',
                'totalAmount' => 50,
                'cancellationInfo' => [
                    'operationTime' => '',
                    'registerTime' => '',
                    'taxPeriodId' => 202203,
                    'comment' => $comment,
                ],
                'sourceDeviceId' => 'davxcvc90876rsdf',
            ],
        ]);

        $incomeInfo = $this->client->income()->cancel($receiptId, $comment);

        self::assertSame($receiptId, $incomeInfo->getApprovedReceiptUuid());
        self::assertSame($comment, $incomeInfo->getCancellationInfo()->getComment());
    }

    public function validationCancelDataProvider(): iterable
    {
        yield ['', CancelCommentType::REFUND, 'ReceiptUuid cannot be empty'];
        yield ['ReceiptUuid', 'InvalidCommentType', 'Comment is invalid. Must be one of: "Чек сформирован ошибочно", "Возврат средств"'];
    }

    /**
     * @dataProvider validationCancelDataProvider
     *
     * @throws \JsonException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Shoman4eg\Nalog\Exception\DomainException
     */
    public function testCancelValidation(string $receiptId, string $comment, string $message): void
    {
        $this->expectExceptionMessage($message);
        $this->client->income()->cancel($receiptId, $comment);
    }
}
