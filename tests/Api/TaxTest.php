<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use PHPUnit\Framework\Attributes\CoversNothing;
use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @internal
 */
#[CoversNothing]
final class TaxTest extends ApiTestCase
{
    public function testHistory(): void
    {
        $data = [
            'records' => [
                [
                    'taxPeriodId' => 202211,
                    'taxAmount' => 12.00,
                    'bonusAmount' => 12.33,
                    'paidAmount' => 44.23,
                    'taxBaseAmount' => 12.23,
                    'chargeDate' => '2022-11-12',
                    'dueDate' => '2022-12-11',
                    'oktmo' => '260000',
                    'regionName' => 'Калининградская область',
                    'kbk' => '',
                    'taxOrganCode' => '',
                    'type' => '',
                    'krsbTaxChargeId' => 0,
                    'receiptCount' => 0,
                ],
            ],
        ];

        $this->appendSuccessJson($data);

        $response = $this->client->tax()->history();

        foreach ($response as $key => $item) {
            $record = $data['records'][$key];
            self::assertSame($record['taxPeriodId'], $item->taxPeriodId);
            self::assertSame($record['taxAmount'], $item->taxAmount);
            self::assertSame($record['bonusAmount'], $item->bonusAmount);
            self::assertSame($record['paidAmount'], $item->paidAmount);
            self::assertEquals(
                $record['chargeDate'] ? new \DateTimeImmutable($record['chargeDate']) : null,
                $item->chargeDate
            );
            self::assertEquals(
                $record['dueDate'] ? new \DateTimeImmutable($record['dueDate']) : null,
                $item->dueDate
            );
            self::assertSame($record['oktmo'], $item->oktmo);
            self::assertSame($record['regionName'], $item->regionName);
            self::assertSame($record['kbk'], $item->kbk);
            self::assertSame($record['taxOrganCode'], $item->taxOrganCode);
            self::assertSame($record['type'], $item->type);
            self::assertSame($record['krsbTaxChargeId'], $item->krsbTaxChargeId);
            self::assertSame($record['receiptCount'], $item->receiptCount);
        }
    }

    public function testPayments(): void
    {
        $data = [
            'records' => [
                [
                    'sourceType' => '',
                    'type' => '',
                    'documentIndex' => '',
                    'amount' => 44.23,
                    'operationDate' => '2022-11-12',
                    'dueDate' => '2022-11-12',
                    'oktmo' => '260000',
                    'kbk' => '',
                    'status' => 'Калининградская область',
                    'taxPeriodId' => 202211,
                    'regionName' => '',
                    'krsbAcceptedDate' => '2022-11-12',
                ],
            ],
        ];

        $this->appendSuccessJson($data);

        $response = $this->client->tax()->payments();

        foreach ($response as $key => $item) {
            $record = $data['records'][$key];
            self::assertSame($record['type'], $item->type);
            self::assertSame($record['sourceType'], $item->sourceType);
            self::assertSame($record['documentIndex'], $item->documentIndex);
            self::assertSame($record['amount'], $item->amount);
            self::assertEquals(new \DateTimeImmutable($record['operationDate']), $item->operationDate);
            self::assertEquals(new \DateTimeImmutable($record['dueDate']), $item->dueDate);
            self::assertSame($record['oktmo'], $item->oktmo);
            self::assertSame($record['kbk'], $item->kbk);
            self::assertSame($record['regionName'], $item->regionName);
            self::assertSame($record['status'], $item->status);
            self::assertSame($record['type'], $item->type);
            self::assertSame($record['taxPeriodId'], $item->taxPeriodId);
            self::assertSame(strtotime($record['krsbAcceptedDate']), $item->krsbAcceptedDate->getTimestamp());
        }
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

        self::assertEquals($data['totalForPayment'], $response->totalForPayment);
        self::assertEquals($data['total'], $response->total);
        self::assertEquals($data['tax'], $response->tax);
        self::assertEquals($data['debt'], $response->debt);
        self::assertEquals($data['overpayment'], $response->overpayment);
        self::assertEquals($data['penalty'], $response->penalty);
        self::assertEquals($data['nominalTax'], $response->nominalTax);
        self::assertEquals($data['nominalOverpayment'], $response->nominalOverpayment);
        self::assertSame($data['taxPeriodId'], $response->taxPeriodId);
        self::assertEquals($data['lastPaymentAmount'], $response->lastPaymentAmount);
        self::assertEquals(
            $data['lastPaymentDate'] ? new \DateTimeImmutable($data['lastPaymentDate']) : null,
            $response->lastPaymentDate
        );
        self::assertSame($data['regions'], $response->regions);
    }
}
