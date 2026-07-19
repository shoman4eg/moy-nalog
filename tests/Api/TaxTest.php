<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use Shoman4eg\Nalog\Tests\ApiTestCase;
use Testo\Assert;
use Testo\Codecov\CoversNothing;
use Testo\Test;

/**
 * @internal
 */
#[Test]
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
            Assert::same($item->taxPeriodId, $record['taxPeriodId']);
            Assert::same($item->taxAmount, $record['taxAmount']);
            Assert::same($item->bonusAmount, $record['bonusAmount']);
            Assert::same($item->paidAmount, $record['paidAmount']);
            Assert::equals($item->chargeDate, $record['chargeDate'] ? new \DateTimeImmutable($record['chargeDate']) : null);
            Assert::equals($item->dueDate, $record['dueDate'] ? new \DateTimeImmutable($record['dueDate']) : null);
            Assert::same($item->oktmo, $record['oktmo']);
            Assert::same($item->regionName, $record['regionName']);
            Assert::same($item->kbk, $record['kbk']);
            Assert::same($item->taxOrganCode, $record['taxOrganCode']);
            Assert::same($item->type, $record['type']);
            Assert::same($item->krsbTaxChargeId, $record['krsbTaxChargeId']);
            Assert::same($item->receiptCount, $record['receiptCount']);
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
            Assert::same($item->type, $record['type']);
            Assert::same($item->sourceType, $record['sourceType']);
            Assert::same($item->documentIndex, $record['documentIndex']);
            Assert::same($item->amount, $record['amount']);
            Assert::equals($item->operationDate, new \DateTimeImmutable($record['operationDate']));
            Assert::equals($item->dueDate, new \DateTimeImmutable($record['dueDate']));
            Assert::same($item->oktmo, $record['oktmo']);
            Assert::same($item->kbk, $record['kbk']);
            Assert::same($item->regionName, $record['regionName']);
            Assert::same($item->status, $record['status']);
            Assert::same($item->type, $record['type']);
            Assert::same($item->taxPeriodId, $record['taxPeriodId']);
            Assert::same($item->krsbAcceptedDate->getTimestamp(), strtotime($record['krsbAcceptedDate']));
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

        Assert::equals($response->totalForPayment, $data['totalForPayment']);
        Assert::equals($response->total, $data['total']);
        Assert::equals($response->tax, $data['tax']);
        Assert::equals($response->debt, $data['debt']);
        Assert::equals($response->overpayment, $data['overpayment']);
        Assert::equals($response->penalty, $data['penalty']);
        Assert::equals($response->nominalTax, $data['nominalTax']);
        Assert::equals($response->nominalOverpayment, $data['nominalOverpayment']);
        Assert::same($response->taxPeriodId, $data['taxPeriodId']);
        Assert::equals($response->lastPaymentAmount, $data['lastPaymentAmount']);
        Assert::equals($response->lastPaymentDate, $data['lastPaymentDate'] ? new \DateTimeImmutable($data['lastPaymentDate']) : null);
        Assert::same($response->regions, $data['regions']);
    }
}
