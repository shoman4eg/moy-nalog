<?php

declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\DTO;
use Shoman4eg\Nalog\Enum\BuyerType;
use Shoman4eg\Nalog\Enum\CancelCommentType;
use Shoman4eg\Nalog\Enum\IncomeType;
use Shoman4eg\Nalog\Enum\PaymentType;
use Shoman4eg\Nalog\Enum\ReceiptType;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @internal
 */
#[CoversNothing]
final class IncomeTest extends ApiTestCase
{
    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    #[DataProvider('clientDataProvider')]
    public function testCreate(?DTO\IncomeClient $client): void
    {
        $receiptId = 'randomReceiptId';
        $this->appendSuccessJson(['approvedReceiptUuid' => $receiptId]);
        $response = $this->client->income()->create('name', 100, 1, null, $client);

        self::assertSame($receiptId, $response->getApprovedReceiptUuid());
    }

    public static function clientDataProvider(): iterable
    {
        yield [new DTO\IncomeClient()];
        yield [new DTO\IncomeClient(null, 'testClient', IncomeType::LEGAL_ENTITY, '1234567890')];
        yield [new DTO\IncomeClient(null, null, IncomeType::INDIVIDUAL, null)];
        yield [new DTO\IncomeClient('phone', 'testClient', IncomeType::FOREIGN_AGENCY)];
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
        $fakeClientWithInn = static fn ($inn) => new DTO\IncomeClient(null, '', IncomeType::LEGAL_ENTITY, $inn);

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
    #[DataProvider('cancellationDataProvider')]
    public function testCancel(string $receiptId, CancelCommentType $comment): void
    {
        $this->appendSuccessJson([
            'incomeInfo' => [
                'approvedReceiptUuid' => $receiptId,
                'name' => '',
                'operationTime' => '',
                'requestTime' => '',
                'paymentType' => PaymentType::CASH->value,
                'partnerCode' => '',
                'totalAmount' => 50,
                'cancellationInfo' => [
                    'operationTime' => '',
                    'registerTime' => '',
                    'taxPeriodId' => 202203,
                    'comment' => $comment->value,
                ],
                'sourceDeviceId' => 'davxcvc90876rsdf',
            ],
        ]);

        $incomeInfo = $this->client->income()->cancel($receiptId, $comment);

        self::assertSame($receiptId, $incomeInfo->getApprovedReceiptUuid());
        self::assertSame($comment->value, $incomeInfo->getCancellationInfo()->getComment());
    }

    public static function cancellationDataProvider(): iterable
    {
        yield ['12345678', CancelCommentType::CANCEL];
        yield ['12345678', CancelCommentType::REFUND];
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function testCancelWithStringComment(): void
    {
        $receiptId = '12345678';
        $comment = CancelCommentType::REFUND->value;
        $this->appendSuccessJson([
            'incomeInfo' => [
                'approvedReceiptUuid' => $receiptId,
                'name' => '',
                'operationTime' => '',
                'requestTime' => '',
                'paymentType' => PaymentType::CASH->value,
                'partnerCode' => null,
                'totalAmount' => 50,
                'cancellationInfo' => [
                    'operationTime' => '',
                    'registerTime' => '',
                    'taxPeriodId' => 202203,
                    'comment' => $comment,
                ],
                'sourceDeviceId' => 'device123',
            ],
        ]);

        $incomeInfo = $this->client->income()->cancel($receiptId, $comment);
        self::assertSame($comment, $incomeInfo->getCancellationInfo()->getComment());
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    #[DataProvider('validationCancelDataProvider')]
    public function testValidationCancel(string $receiptId, string $comment, string $message): void
    {
        $this->expectExceptionMessage($message);
        $this->client->income()->cancel($receiptId, $comment);
    }

    public static function validationCancelDataProvider(): iterable
    {
        yield ['', CancelCommentType::REFUND->value, 'ReceiptUuid cannot be empty'];
        yield [
            'ReceiptUuid',
            'InvalidCommentType',
            'Comment is invalid. Must be one of: "Чек сформирован ошибочно", "Возврат средств"',
        ];
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function testList(): void
    {
        $this->appendSuccessJson([
            'currentOffset' => 0,
            'currentLimit' => 10,
            'hasMore' => false,
            'content' => [],
        ]);

        $result = $this->client->income()->list();
        self::assertSame(0, $result->getCurrentOffset());
        self::assertSame(10, $result->getCurrentLimit());
        self::assertFalse($result->isHasMore());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function testListWithEnumFilters(): void
    {
        $this->appendSuccessJson([
            'currentOffset' => 0,
            'currentLimit' => 10,
            'hasMore' => false,
            'content' => [],
        ]);

        $this->client->income()->list(
            buyerType: BuyerType::PERSON,
            receiptType: ReceiptType::REGISTERED,
        );

        $query = [];
        parse_str($this->mock->getLastRequest()->getUri()->getQuery(), $query);
        self::assertSame(BuyerType::PERSON->value, $query['buyerType']);
        self::assertSame(ReceiptType::REGISTERED->value, $query['receiptType']);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    #[DataProvider('listLimitClampDataProvider')]
    public function testListLimitClamped(int $limit, int $expected): void
    {
        $this->appendSuccessJson([
            'currentOffset' => 0,
            'currentLimit' => $expected,
            'hasMore' => false,
            'content' => [],
        ]);

        $this->client->income()->list(limit: $limit);

        $query = [];
        parse_str($this->mock->getLastRequest()->getUri()->getQuery(), $query);
        self::assertSame((string)$expected, $query['limit']);
    }

    public static function listLimitClampDataProvider(): iterable
    {
        yield 'below min' => [0, 1];
        yield 'above max' => [200, 100];
        yield 'within range' => [50, 50];
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function testCreateMultipleItemsEmptyThrows(): void
    {
        $this->expectExceptionMessage('Items cannot be empty');
        $this->client->income()->createMultipleItems([]);
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    #[DataProvider('calculationItemsDataProvider')]
    public function testCalculateAmount(array $items, mixed $expected): void
    {
        $this->appendSuccessJson(['approvedReceiptUuid' => 'randomReceiptId']);

        $serviceItems = array_map(fn ($item) => new DTO\IncomeServiceItem(...$item), $items);
        $this->client->income()->createMultipleItems($serviceItems);
        $request = json_decode($this->mock->getLastRequest()->getBody()->getContents(), true);

        self::assertEquals($request['totalAmount'], $expected);
    }

    public static function calculationItemsDataProvider(): iterable
    {
        $name = 'randomName';
        yield [[[$name, 100, 1], [$name, 200, 2], [$name, 300, 3]], 1400];
        yield [[[$name, 30.23, 1], [$name, 12.33, 8], [$name, 32.44, 9]], 420.83];
        yield [[[$name, '30.23', 1], [$name, '12.33', 8], [$name, '32.44', 9]], '420.83'];
    }
}
