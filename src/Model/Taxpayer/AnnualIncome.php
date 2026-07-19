<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Taxpayer;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @phpstan-type AnnualIncomeData array{
 *     totalIncomeAmount: float|int,
 *     maxTotalIncomeThresholdExceeded: bool,
 *     annualIncomeThreshold: float|int,
 *     availableIncomeToExceedThreshold: float|int,
 *     annualIncomeStatus: string,
 *     updatedTime: string,
 * }
 */
final readonly class AnnualIncome implements CreatableFromArray
{
    public float $totalIncomeAmount;
    public bool $maxTotalIncomeThresholdExceeded;
    public float $annualIncomeThreshold;
    public float $availableIncomeToExceedThreshold;
    public string $annualIncomeStatus;
    public \DateTimeImmutable $updatedTime;

    /**
     * @param AnnualIncomeData $data
     *
     * @throws \Exception
     */
    private function __construct(array $data)
    {
        $this->totalIncomeAmount = $data['totalIncomeAmount'];
        $this->maxTotalIncomeThresholdExceeded = $data['maxTotalIncomeThresholdExceeded'];
        $this->annualIncomeThreshold = $data['annualIncomeThreshold'];
        $this->availableIncomeToExceedThreshold = $data['availableIncomeToExceedThreshold'];
        $this->annualIncomeStatus = $data['annualIncomeStatus'];
        $this->updatedTime = new \DateTimeImmutable($data['updatedTime']);
    }

    /**
     * @param AnnualIncomeData $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
