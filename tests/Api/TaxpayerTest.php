<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use PHPUnit\Framework\Attributes\CoversNothing;
use Shoman4eg\Nalog\Model\Taxpayer\AnnualIncome;
use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @internal
 */
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

        self::assertFalse($debts->hasDebts);
        self::assertSame(0.0, $debts->totalUnpaid);
        self::assertSame(0.0, $debts->debts);
    }

    public function testDebtsWithOutstandingAmount(): void
    {
        $this->appendSuccessJson([
            'hasDebts' => true,
            'totalUnpaid' => 1234.56,
            'debts' => 1000.0,
        ]);

        $debts = $this->client->taxpayer()->debts();

        self::assertTrue($debts->hasDebts);
        self::assertSame(1234.56, $debts->totalUnpaid);
        self::assertSame(1000.0, $debts->debts);
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

        self::assertSame(8500.0, $bonus->bonusAmount);
        self::assertSame(759100.0, $bonus->totalIncomeAmount);
        self::assertFalse($bonus->maxTotalIncomeThresholdExceeded);
        self::assertSame(2400000.0, $bonus->annualIncomeThreshold);
        self::assertSame('NORMAL', $bonus->annualIncomeStatus);
        self::assertInstanceOf(\DateTimeImmutable::class, $bonus->updatedTime);
        self::assertNull($bonus->teenBonusAmount);
        self::assertNull($bonus->teenBonusUpdatedTime);

        self::assertCount(2, $bonus->totalIncomeByYears);
        self::assertArrayHasKey('2025', $bonus->totalIncomeByYears);
        $year2025 = $bonus->totalIncomeByYears['2025'];
        self::assertInstanceOf(AnnualIncome::class, $year2025);
        self::assertSame(1090000.0, $year2025->totalIncomeAmount);
        self::assertSame(1310000.0, $year2025->availableIncomeToExceedThreshold);
        self::assertInstanceOf(\DateTimeImmutable::class, $year2025->updatedTime);
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

        self::assertSame(759100.0, $bonus->totalIncomeAmount);
        self::assertSame(1640900.0, $bonus->availableIncomeToExceedThreshold);
        self::assertSame([], $bonus->totalIncomeByYears);
    }
}
