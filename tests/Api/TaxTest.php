<?php

namespace Shoman4eg\Nalog\Tests\Api;

use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @internal
 * @coversNothing
 */
class TaxTest extends ApiTestCase
{
    public function testHistory(): void
    {
        self::markTestSkipped();
    }

    public function testPayments(): void
    {
        self::markTestSkipped();
    }

    public function testGet(): void
    {
        $data = [
            'totalForPayment' => 0,
            'total' => 0,
            'tax' => 0,
            'debt' => 0,
            'overpayment' => 0,
            'penalty' => 0,
            'nominalTax' => 0,
            'nominalOverpayment' => 0,
            'taxPeriodId' => 202305,
            'lastPaymentAmount' => null,
            'lastPaymentDate' => '2023-12-03',
            'regions' => [],
        ];

        $this->appendSuccessJson($data);

        $response = $this->client->tax()->get();

        self::assertEquals($data['totalForPayment'], $response->getTotalForPayment());
        self::assertEquals($data['total'], $response->getTotal());
        self::assertEquals($data['tax'], $response->getTax());
        self::assertEquals($data['debt'], $response->getDebt());
        self::assertEquals($data['overpayment'], $response->getOverpayment());
        self::assertEquals($data['penalty'], $response->getPenalty());
        self::assertEquals($data['nominalTax'], $response->getNominalTax());
        self::assertEquals($data['nominalOverpayment'], $response->getNominalOverpayment());
        self::assertSame($data['taxPeriodId'], $response->getTaxPeriodId());
        self::assertEquals($data['lastPaymentAmount'], $response->getLastPaymentAmount());
        self::assertInstanceOf(\DateTimeImmutable::class, $response->getLastPaymentDate());
        self::assertSame(
            strtotime($data['lastPaymentDate']),
            $response->getLastPaymentDate()->getTimestamp()
        );
        self::assertSame($data['regions'], $response->getRegions());
    }
}
