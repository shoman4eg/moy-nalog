<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests;

use Shoman4eg\Nalog\DTO\IncomeClient;
use Shoman4eg\Nalog\DTO\IncomeServiceItem;
use Shoman4eg\Nalog\Enum\BuyerType;
use Shoman4eg\Nalog\Enum\CancelCommentType;
use Shoman4eg\Nalog\Enum\IncomeType;
use Shoman4eg\Nalog\Enum\PaymentType;
use Shoman4eg\Nalog\Enum\ReceiptType;
use Testo\Assert;
use Testo\Test;

#[Test]
final class EnumAndDtoTest
{
    public function testEnumsJsonSerialize(): void
    {
        Assert::same(json_encode(BuyerType::PERSON, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"PERSON"');
        Assert::same(json_encode(BuyerType::COMPANY, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"COMPANY"');
        Assert::same(json_encode(BuyerType::FOREIGN_AGENCY, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"FOREIGN_AGENCY"');

        Assert::same(json_encode(PaymentType::CASH, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"CASH"');
        Assert::same(json_encode(PaymentType::ACCOUNT, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"ACCOUNT"');

        Assert::same(json_encode(IncomeType::INDIVIDUAL, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"FROM_INDIVIDUAL"');
        Assert::same(json_encode(IncomeType::LEGAL_ENTITY, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"FROM_LEGAL_ENTITY"');
        Assert::same(json_encode(IncomeType::FOREIGN_AGENCY, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"FROM_FOREIGN_AGENCY"');

        Assert::same(json_encode(ReceiptType::REGISTERED, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"REGISTERED"');
        Assert::same(json_encode(ReceiptType::CANCELLED, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"CANCELLED"');

        Assert::same(json_encode(CancelCommentType::CANCEL, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"Чек сформирован ошибочно"');
        Assert::same(json_encode(CancelCommentType::REFUND, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), '"Возврат средств"');
    }

    public function testIncomeClientGetIncomeTypeReturnsEnum(): void
    {
        $client = new IncomeClient(incomeType: IncomeType::LEGAL_ENTITY);
        Assert::same($client->incomeType, IncomeType::LEGAL_ENTITY);
        Assert::same($client->incomeType->value, 'FROM_LEGAL_ENTITY');
    }

    public function testIncomeClientDefaultIncomeType(): void
    {
        $client = new IncomeClient();
        Assert::same($client->incomeType, IncomeType::INDIVIDUAL);
    }

    public function testIncomeClientJsonSerialize(): void
    {
        $client = new IncomeClient('79001234567', 'Test LLC', IncomeType::LEGAL_ENTITY, '1234567890');
        $data = $client->jsonSerialize();

        Assert::same($data['incomeType'], 'FROM_LEGAL_ENTITY');
        Assert::same($data['contactPhone'], '79001234567');
        Assert::same($data['displayName'], 'Test LLC');
        Assert::same($data['inn'], '1234567890');
    }

    public function testIncomeServiceItemGetTotalAmount(): void
    {
        $item = new IncomeServiceItem('service', 30.23, 3);
        Assert::same((string)$item->getTotalAmount(), '90.69');
    }

    public function testIncomeServiceItemGetters(): void
    {
        $item = new IncomeServiceItem('my service', 99.5, 2);
        Assert::same($item->name, 'my service');
        Assert::same($item->amount, 99.5);
        Assert::same($item->quantity, 2);
    }
}
