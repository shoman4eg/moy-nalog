<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use Shoman4eg\Nalog\Model\Taxpayer\AnnualIncome;
use Shoman4eg\Nalog\Tests\ApiTestCase;
use Testo\Assert;
use Testo\Codecov\CoversNothing;
use Testo\Test;

/**
 * @internal
 */
#[Test]
#[CoversNothing]
final class TaxpayerTest extends ApiTestCase
{
    public function testDebts(): void
    {
        $this->appendSuccessJson([
            'hasDebts' => false,
            'totalUnpaid' => 0,
            'debts' => 0,
        ]);

        $debts = $this->client->taxpayer()->debts();

        Assert::false($debts->hasDebts);
        Assert::same($debts->totalUnpaid, 0.0);
        Assert::same($debts->debts, 0.0);
    }

    public function testDebtsWithOutstandingAmount(): void
    {
        $this->appendSuccessJson([
            'hasDebts' => true,
            'totalUnpaid' => 1234.56,
            'debts' => 1000.0,
        ]);

        $debts = $this->client->taxpayer()->debts();

        Assert::true($debts->hasDebts);
        Assert::same($debts->totalUnpaid, 1234.56);
        Assert::same($debts->debts, 1000.0);
    }

    public function testBonus(): void
    {
        $this->appendSuccessJson([
            'bonusAmount' => 8500,
            'totalIncomeAmount' => 759100,
            'maxTotalIncomeThresholdExceeded' => false,
            'annualIncomeThreshold' => 2400000,
            'availableIncomeToExceedThreshold' => 1640900,
            'annualIncomeStatus' => 'NORMAL',
            'updatedTime' => '2026-07-14T21:15:27.647651Z',
            'totalIncomeByYears' => [
                '2025' => [
                    'totalIncomeAmount' => 1090000,
                    'maxTotalIncomeThresholdExceeded' => false,
                    'annualIncomeThreshold' => 2400000,
                    'availableIncomeToExceedThreshold' => 1310000,
                    'annualIncomeStatus' => 'NORMAL',
                    'updatedTime' => '2025-12-29T00:23:34.390419Z',
                ],
                '2026' => [
                    'totalIncomeAmount' => 759100,
                    'maxTotalIncomeThresholdExceeded' => false,
                    'annualIncomeThreshold' => 2400000,
                    'availableIncomeToExceedThreshold' => 1640900,
                    'annualIncomeStatus' => 'NORMAL',
                    'updatedTime' => '2026-07-14T21:15:27.647651Z',
                ],
            ],
            'teenBonusAmount' => null,
            'teenBonusUpdatedTime' => null,
        ]);

        $bonus = $this->client->taxpayer()->bonus();

        Assert::same($bonus->bonusAmount, 8500.0);
        Assert::same($bonus->totalIncomeAmount, 759100.0);
        Assert::false($bonus->maxTotalIncomeThresholdExceeded);
        Assert::same($bonus->annualIncomeThreshold, 2400000.0);
        Assert::same($bonus->annualIncomeStatus, 'NORMAL');
        Assert::instanceOf($bonus->updatedTime, \DateTimeImmutable::class);
        Assert::null($bonus->teenBonusAmount);
        Assert::null($bonus->teenBonusUpdatedTime);

        Assert::count($bonus->totalIncomeByYears, 2);
        Assert::true(\array_key_exists('2025', $bonus->totalIncomeByYears));
        $year2025 = $bonus->totalIncomeByYears['2025'];
        Assert::instanceOf($year2025, AnnualIncome::class);
        Assert::same($year2025->totalIncomeAmount, 1090000.0);
        Assert::same($year2025->availableIncomeToExceedThreshold, 1310000.0);
        Assert::instanceOf($year2025->updatedTime, \DateTimeImmutable::class);
    }

    public function testBonusHandlesExponentialNumbers(): void
    {
        // The API returns amounts in exponential notation (e.g. 7.591E+5); ensure they decode.
        $this->appendSuccessJsonString(
            '{"bonusAmount":0,"totalIncomeAmount":7.591E+5,"maxTotalIncomeThresholdExceeded":false,'
            .'"annualIncomeThreshold":2400000,"availableIncomeToExceedThreshold":1.6409E+6,'
            .'"annualIncomeStatus":"NORMAL","updatedTime":"2026-07-14T21:15:27.647651Z",'
            .'"totalIncomeByYears":{},"teenBonusAmount":null,"teenBonusUpdatedTime":null}'
        );

        $bonus = $this->client->taxpayer()->bonus();

        Assert::same($bonus->totalIncomeAmount, 759100.0);
        Assert::same($bonus->availableIncomeToExceedThreshold, 1640900.0);
        Assert::same($bonus->totalIncomeByYears, []);
    }
}
