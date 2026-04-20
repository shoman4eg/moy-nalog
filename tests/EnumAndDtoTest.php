<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Shoman4eg\Nalog\DTO\IncomeClient;
use Shoman4eg\Nalog\DTO\IncomeServiceItem;
use Shoman4eg\Nalog\Enum\BuyerType;
use Shoman4eg\Nalog\Enum\CancelCommentType;
use Shoman4eg\Nalog\Enum\IncomeType;
use Shoman4eg\Nalog\Enum\PaymentType;
use Shoman4eg\Nalog\Enum\ReceiptType;

/**
 * @internal
 */
#[CoversNothing]
final class EnumAndDtoTest extends TestCase
{
    public function testEnumsJsonSerialize(): void
    {
        self::assertSame('"PERSON"', json_encode(BuyerType::PERSON, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        self::assertSame('"COMPANY"', json_encode(BuyerType::COMPANY, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        self::assertSame('"FOREIGN_AGENCY"', json_encode(BuyerType::FOREIGN_AGENCY, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        self::assertSame('"CASH"', json_encode(PaymentType::CASH, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        self::assertSame('"ACCOUNT"', json_encode(PaymentType::ACCOUNT, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        self::assertSame('"FROM_INDIVIDUAL"', json_encode(IncomeType::INDIVIDUAL, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        self::assertSame('"FROM_LEGAL_ENTITY"', json_encode(IncomeType::LEGAL_ENTITY, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        self::assertSame('"FROM_FOREIGN_AGENCY"', json_encode(IncomeType::FOREIGN_AGENCY, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        self::assertSame('"REGISTERED"', json_encode(ReceiptType::REGISTERED, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        self::assertSame('"CANCELLED"', json_encode(ReceiptType::CANCELLED, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        self::assertSame('"Чек сформирован ошибочно"', json_encode(CancelCommentType::CANCEL, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        self::assertSame('"Возврат средств"', json_encode(CancelCommentType::REFUND, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
    }

    public function testIncomeClientGetIncomeTypeReturnsEnum(): void
    {
        $client = new IncomeClient(incomeType: IncomeType::LEGAL_ENTITY);
        self::assertSame(IncomeType::LEGAL_ENTITY, $client->incomeType);
        self::assertSame('FROM_LEGAL_ENTITY', $client->incomeType->value);
    }

    public function testIncomeClientDefaultIncomeType(): void
    {
        $client = new IncomeClient();
        self::assertSame(IncomeType::INDIVIDUAL, $client->incomeType);
    }

    public function testIncomeClientJsonSerialize(): void
    {
        $client = new IncomeClient('79001234567', 'Test LLC', IncomeType::LEGAL_ENTITY, '1234567890');
        $data = $client->jsonSerialize();

        self::assertSame('FROM_LEGAL_ENTITY', $data['incomeType']);
        self::assertSame('79001234567', $data['contactPhone']);
        self::assertSame('Test LLC', $data['displayName']);
        self::assertSame('1234567890', $data['inn']);
    }

    public function testIncomeServiceItemGetTotalAmount(): void
    {
        $item = new IncomeServiceItem('service', 30.23, 3);
        self::assertSame('90.69', (string) $item->getTotalAmount());
    }

    public function testIncomeServiceItemGetters(): void
    {
        $item = new IncomeServiceItem('my service', 99.5, 2);
        self::assertSame('my service', $item->name);
        self::assertSame(99.5, $item->amount);
        self::assertSame(2, $item->quantity);
    }
}
