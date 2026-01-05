<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\Api\Income;
use Shoman4eg\Nalog\DTO;
use Shoman4eg\Nalog\Enum\CancelCommentType;
use Shoman4eg\Nalog\Enum\IncomeType;
use Shoman4eg\Nalog\Enum\PaymentType;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\Tests\ApiTestCase;
use Shoman4eg\Nalog\Util\JSON;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 */
#[CoversClass(Income::class)]
final class IncomeTest extends ApiTestCase
{
    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    #[TestWith([new DTO\IncomeClient()])]
    #[TestWith([new DTO\IncomeClient(null, 'testClient', IncomeType::LEGAL_ENTITY, '1234567890')])]
    #[TestWith([new DTO\IncomeClient(null, null, IncomeType::INDIVIDUAL, null)])]
    #[TestWith([new DTO\IncomeClient('phone', 'testClient', IncomeType::FOREIGN_AGENCY)])]
    public function testCreateWithIncomeClient(?DTO\IncomeClient $client): void
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
    #[TestWith([[new DTO\IncomeServiceItem('test1', '100', 1)], new DTO\IncomeClient(), '100'])]
    #[TestWith([
        [new DTO\IncomeServiceItem('test1', '30.23', 1), new DTO\IncomeServiceItem('test2', 12.33, 8), new DTO\IncomeServiceItem('test2', 32.44, 9)],
        new DTO\IncomeClient(null, 'testClient', IncomeType::LEGAL_ENTITY, '1234567890'),
        '420.83',
    ])]
    public function testCreateMultipleItems(array $items, DTO\IncomeClient $client, string $expected): void
    {
        $receiptId = 'randomReceiptId';
        $this->appendSuccessJson(['approvedReceiptUuid' => $receiptId]);

        $response = $this->client->income()->createMultipleItems($items, null, $client);
        $request = JSON::decode($this->mock->getLastRequest()?->getBody()->getContents(), true);

        self::assertSame($request['client']['contactPhone'], $client->getContactPhone());
        self::assertSame($request['client']['displayName'], $client->getDisplayName());
        self::assertSame($request['client']['incomeType'], $client->getIncomeType());
        self::assertSame($request['client']['inn'], $client->getInn());
        self::assertCount(count($items), $request['services']);
        self::assertSame($request['paymentType'], PaymentType::CASH);
        self::assertSame($request['totalAmount'], $expected);
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

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    #[DataProvider('validationCreateDataProvider')]
    public function testValidationCreate(
        string $name,
        float|int|string $amount,
        float|int $quantity,
        ?DTO\IncomeClient $client,
        string $message
    ): void {
        $this->expectExceptionMessage($message);
        $this->client->income()->create($name, $amount, $quantity, null, $client);
    }

    public static function validationCreateDataProvider(): iterable
    {
        $fakeClientWithInn = static fn (string $inn): DTO\IncomeClient => new DTO\IncomeClient(null, '', IncomeType::LEGAL_ENTITY, $inn);

        yield ['', 100, 1, null, 'Name of item[0] cannot be empty'];
        yield ['name', '', 1, null, 'Amount of item[0] must be int or float'];
        yield ['name', -1, 1, null, 'Amount of item[0] must be greater than 0'];
        yield ['name', 1, 0, null, 'Quantity of item[0] cannot be empty'];
        yield ['name', 1, 1, $fakeClientWithInn(''), 'Client INN cannot be empty'];
        yield ['name', 1, 1, $fakeClientWithInn('aaaa'), 'Client INN must contain only numbers'];
        yield ['name', 1, 1, $fakeClientWithInn(str_repeat('1', 9)), 'Client INN length must been 10 or 12'];
        yield ['name', 1, 1, $fakeClientWithInn(str_repeat('1', 11)), 'Client INN length must been 10 or 12'];
        yield ['name', 1, 1, $fakeClientWithInn(str_repeat('1', 13)), 'Client INN length must been 10 or 12'];
        yield ['name', 1, 1, $fakeClientWithInn('1234567890'), 'Client DisplayName cannot be empty'];
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    #[TestWith(['12345678', CancelCommentType::CANCEL])]
    #[TestWith(['12345678', CancelCommentType::REFUND])]
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

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    #[TestWith(['', CancelCommentType::REFUND, 'ReceiptUuid cannot be empty'])]
    #[TestWith(['ReceiptUuid', 'InvalidCommentType', 'Comment is invalid. Must be one of: "Чек сформирован ошибочно", "Возврат средств"'])]
    public function testValidationCancel(string $receiptId, string $comment, string $message): void
    {
        $this->expectExceptionMessage($message);
        $this->client->income()->cancel($receiptId, $comment);
    }
}
