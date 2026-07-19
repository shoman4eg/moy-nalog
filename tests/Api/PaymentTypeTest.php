<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use Shoman4eg\Nalog\Model\PaymentType\PaymentType;
use Shoman4eg\Nalog\Tests\ApiTestCase;
use Testo\Assert;
use Testo\Codecov\CoversNothing;
use Testo\Test;

/**
 * @internal
 */
#[Test]
#[CoversNothing]
final class PaymentTypeTest extends ApiTestCase
{
    public function testTable(): void
    {
        $item = [
            'id' => 151293,
            'type' => 'ACCOUNT',
            'bankName' => 'АО "SUPER BANK"',
            'bankBik' => '000000000',
            'currentAccount' => '10000000000000000000',
            'corrAccount' => '10000000000000000000',
            'phone' => null,
            'bankId' => null,
            'favorite' => true,
            'availableForPa' => false,
        ];
        $this->appendSuccessJson(['items' => [$item]]);

        $response = $this->client->paymentType()->table();
        /** @var PaymentType $paymentType */
        $paymentType = $response[0];
        Assert::count($response, 1);
        Assert::same($paymentType->id, $item['id']);
        Assert::same($paymentType->phone, $item['phone']);
        Assert::same($paymentType->bankId, $item['bankId']);
        Assert::same($paymentType->bankName, $item['bankName']);
        Assert::same($paymentType->bankBik, $item['bankBik']);
        Assert::same($paymentType->corrAccount, $item['corrAccount']);
        Assert::same($paymentType->type, $item['type']);
        Assert::same($paymentType->currentAccount, $item['currentAccount']);
        Assert::same($paymentType->availableForPa, $item['availableForPa']);
        Assert::same($paymentType->favorite, $item['favorite']);
    }
}
