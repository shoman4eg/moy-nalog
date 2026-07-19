<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Taxpayer;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @phpstan-type DebtsData array{
 *     hasDebts: bool,
 *     totalUnpaid: float|int,
 *     debts: float|int,
 * }
 */
final readonly class Debts implements CreatableFromArray
{
    public bool $hasDebts;
    public float $totalUnpaid;
    public float $debts;

    /**
     * @param DebtsData $data
     */
    private function __construct(array $data)
    {
        $this->hasDebts = $data['hasDebts'];
        $this->totalUnpaid = $data['totalUnpaid'];
        $this->debts = $data['debts'];
    }

    /**
     * @param DebtsData $data
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
