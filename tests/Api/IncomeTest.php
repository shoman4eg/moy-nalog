<?php

declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\DTO;
use Shoman4eg\Nalog\Enum\CancelCommentType;
use Shoman4eg\Nalog\Enum\IncomeType;
use Shoman4eg\Nalog\Enum\PaymentType;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 *
 * @coversNothing
 */
final class IncomeTest extends ApiTestCase
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
     * @throws ClientExceptionInterface
     * @throws DomainException
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
     * @throws ClientExceptionInterface
     * @throws DomainException
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
        $fakeClientWithInn = static fn ($inn) => new DTO\IncomeClient(null, '', IncomeType::LEGAL_ENTITY, $inn);

        yield ['', 100, 1, null, 'Name of item[0] cannot be empty'];
        yield ['name', '', 1, null, 'Amount of item[0] must be int or float'];
        yield ['name', -1, 1, null, 'Amount of item[0] must be greater than 0'];
        yield ['name', 1, 0, null, 'Quantity of item[0] cannot be empty'];
        yield ['name', 1, 'zero', null, 'Quantity of item[0] must be int or float'];
        yield ['name', 1, 1, $fakeClientWithInn(''), 'Client INN cannot be empty'];
        yield ['name', 1, 1, $fakeClientWithInn('aaaa'), 'Client INN must contain only numbers'];
        yield ['name', 1, 1, $fakeClientWithInn(str_repeat('1', 9)), 'Client INN length must been 10 or 12'];
        yield ['name', 1, 1, $fakeClientWithInn(str_repeat('1', 11)), 'Client INN length must been 10 or 12'];
        yield ['name', 1, 1, $fakeClientWithInn(str_repeat('1', 13)), 'Client INN length must been 10 or 12'];
        yield ['name', 1, 1, $fakeClientWithInn('1234567890'), 'Client DisplayName cannot be empty'];
    }

    /**
     * @dataProvider validationCreateDataProvider
     *
     * @param mixed $amount
     * @param mixed $quantity
     *
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function testValidationCreate(
        string $name,
        $amount,
        $quantity,
        ?DTO\IncomeClient $client,
        string $message
    ): void {
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
     * @throws ClientExceptionInterface
     * @throws DomainException
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
        yield [
            'ReceiptUuid',
            'InvalidCommentType',
            'Comment is invalid. Must be one of: "Чек сформирован ошибочно", "Возврат средств"',
        ];
    }

    /**
     * @dataProvider validationCancelDataProvider
     *
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function testValidationCancel(string $receiptId, string $comment, string $message): void
    {
        $this->expectExceptionMessage($message);
        $this->client->income()->cancel($receiptId, $comment);
    }

    public function calculationItemsDataProvider(): iterable
    {
        $name = 'randomName';
        yield [[[$name, 100, 1], [$name, 200, 2], [$name, 300, 3]], 1400];
        yield [[[$name, 30.23, 1], [$name, 12.33, 8], [$name, 32.44, 9]], 420.83];
        yield [[[$name, '30.23', 1], [$name, '12.33', 8], [$name, '32.44', 9]], '420.83'];
    }

    /**
     * @dataProvider calculationItemsDataProvider
     *
     * @param mixed $expected
     *
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function testCalculateAmount(array $items, $expected): void
    {
        $this->appendSuccessJson(['approvedReceiptUuid' => 'randomReceiptId']);

        $serviceItems = array_map(fn ($item) => new DTO\IncomeServiceItem(...$item), $items);
        $this->client->income()->createMultipleItems($serviceItems);
        $request = json_decode($this->mock->getLastRequest()->getBody()->getContents(), true);

        self::assertEquals($request['totalAmount'], $expected);
    }
}
