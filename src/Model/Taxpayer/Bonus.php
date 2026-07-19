<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Taxpayer;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @phpstan-import-type AnnualIncomeData from AnnualIncome
 *
 * @phpstan-type BonusData array{
 *     bonusAmount: float|int,
 *     totalIncomeAmount: float|int,
 *     maxTotalIncomeThresholdExceeded: bool,
 *     annualIncomeThreshold: float|int,
 *     availableIncomeToExceedThreshold: float|int,
 *     annualIncomeStatus: string,
 *     updatedTime: string,
 *     totalIncomeByYears: array<string, AnnualIncomeData>,
 *     teenBonusAmount: null|float|int,
 *     teenBonusUpdatedTime: null|string,
 * }
 */
final readonly class Bonus implements CreatableFromArray
{
    public float $bonusAmount;
    public float $totalIncomeAmount;
    public bool $maxTotalIncomeThresholdExceeded;
    public float $annualIncomeThreshold;
    public float $availableIncomeToExceedThreshold;
    public string $annualIncomeStatus;
    public \DateTimeImmutable $updatedTime;

    /** @var array<string, AnnualIncome> */
    public array $totalIncomeByYears;

    public ?float $teenBonusAmount;
    public ?\DateTimeImmutable $teenBonusUpdatedTime;

    /**
     * @param BonusData $data
     *
     * @throws \Exception
     */
    private function __construct(array $data)
    {
        $this->bonusAmount = $data['bonusAmount'];
        $this->totalIncomeAmount = $data['totalIncomeAmount'];
        $this->maxTotalIncomeThresholdExceeded = $data['maxTotalIncomeThresholdExceeded'];
        $this->annualIncomeThreshold = $data['annualIncomeThreshold'];
        $this->availableIncomeToExceedThreshold = $data['availableIncomeToExceedThreshold'];
        $this->annualIncomeStatus = $data['annualIncomeStatus'];
        $this->updatedTime = new \DateTimeImmutable($data['updatedTime']);
        $this->totalIncomeByYears = array_map(
            AnnualIncome::createFromArray(...),
            $data['totalIncomeByYears']
        );
        $this->teenBonusAmount = $data['teenBonusAmount'];
        $this->teenBonusUpdatedTime = $data['teenBonusUpdatedTime'] !== null
            ? new \DateTimeImmutable($data['teenBonusUpdatedTime'])
            : null;
    }

    /**
     * @param BonusData $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
