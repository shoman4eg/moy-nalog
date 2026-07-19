<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Tax;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-type TaxData array{
 *     totalForPayment: float|int,
 *     total: float|int,
 *     tax: float|int,
 *     debt: float|int,
 *     overpayment: float|int,
 *     penalty: float|int,
 *     nominalTax: float|int,
 *     nominalOverpayment: float|int,
 *     taxPeriodId: int,
 *     lastPaymentAmount: null|float|int,
 *     lastPaymentDate: null|string,
 *     regions: array<int, mixed>,
 * }
 */
final readonly class Tax implements CreatableFromArray
{
    public float $totalForPayment;
    public float $total;
    public float $tax;
    public float $debt;
    public float $overpayment;
    public float $penalty;
    public float $nominalTax;
    public float $nominalOverpayment;
    public int $taxPeriodId;
    public ?float $lastPaymentAmount;
    public ?\DateTimeImmutable $lastPaymentDate;

    /** @var array<int, mixed> */
    public array $regions;

    /**
     * @param TaxData $data
     *
     * @throws \Exception
     */
    private function __construct(array $data)
    {
        $this->totalForPayment = $data['totalForPayment'];
        $this->total = $data['total'];
        $this->tax = $data['tax'];
        $this->debt = $data['debt'];
        $this->overpayment = $data['overpayment'];
        $this->penalty = $data['penalty'];
        $this->nominalTax = $data['nominalTax'];
        $this->nominalOverpayment = $data['nominalOverpayment'];
        $this->taxPeriodId = $data['taxPeriodId'];
        $this->lastPaymentAmount = $data['lastPaymentAmount'];
        $this->lastPaymentDate = $data['lastPaymentDate'] ? new \DateTimeImmutable($data['lastPaymentDate']) : null;
        $this->regions = $data['regions'];
    }

    /**
     * @param TaxData $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
