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
            self::assertSame($record['taxPeriodId'], $item->getTaxPeriodId());
            self::assertSame($record['taxAmount'], $item->getTaxAmount());
            self::assertSame($record['bonusAmount'], $item->getBonusAmount());
            self::assertSame($record['paidAmount'], $item->getPaidAmount());
            self::assertEquals(
                $record['chargeDate'] ? new \DateTimeImmutable($record['chargeDate']) : null,
                $item->getChargeDate()
            );
            self::assertEquals(
                $record['dueDate'] ? new \DateTimeImmutable($record['dueDate']) : null,
                $item->getDueDate()
            );
            self::assertSame($record['oktmo'], $item->getOktmo());
            self::assertSame($record['regionName'], $item->getRegionName());
            self::assertSame($record['kbk'], $item->getKbk());
            self::assertSame($record['taxOrganCode'], $item->getTaxOrganCode());
            self::assertSame($record['type'], $item->getType());
            self::assertSame($record['krsbTaxChargeId'], $item->getKrsbTaxChargeId());
            self::assertSame($record['receiptCount'], $item->getReceiptCount());
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
            self::assertSame($record['type'], $item->getType());
            self::assertSame($record['sourceType'], $item->getSourceType());
            self::assertSame($record['documentIndex'], $item->getDocumentIndex());
            self::assertSame($record['amount'], $item->getAmount());
            self::assertEquals(new \DateTimeImmutable($record['operationDate']), $item->getOperationDate());
            self::assertEquals(new \DateTimeImmutable($record['dueDate']), $item->getDueDate());
            self::assertSame($record['oktmo'], $item->getOktmo());
            self::assertSame($record['kbk'], $item->getKbk());
            self::assertSame($record['regionName'], $item->getRegionName());
            self::assertSame($record['status'], $item->getStatus());
            self::assertSame($record['type'], $item->getType());
            self::assertSame($record['taxPeriodId'], $item->getTaxPeriodId());
            self::assertSame(strtotime($record['krsbAcceptedDate']), $item->getKrsbAcceptedDate()->getTimestamp());
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
        self::assertEquals(
            $data['lastPaymentDate'] ? new \DateTimeImmutable($data['lastPaymentDate']) : null,
            $response->getLastPaymentDate()
        );
        self::assertSame($data['regions'], $response->getRegions());
    }
}
