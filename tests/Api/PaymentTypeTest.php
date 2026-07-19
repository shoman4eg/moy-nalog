<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use PHPUnit\Framework\Attributes\CoversNothing;
use Shoman4eg\Nalog\Model\PaymentType\PaymentType;
use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @internal
 */
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
        self::assertCount(1, $response);
        self::assertSame($item['id'], $paymentType->id);
        self::assertSame($item['phone'], $paymentType->phone);
        self::assertSame($item['bankId'], $paymentType->bankId);
        self::assertSame($item['bankName'], $paymentType->bankName);
        self::assertSame($item['bankBik'], $paymentType->bankBik);
        self::assertSame($item['corrAccount'], $paymentType->corrAccount);
        self::assertSame($item['type'], $paymentType->type);
        self::assertSame($item['currentAccount'], $paymentType->currentAccount);
        self::assertSame($item['availableForPa'], $paymentType->availableForPa);
        self::assertSame($item['favorite'], $paymentType->favorite);
    }
}
