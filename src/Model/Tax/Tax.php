<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Tax;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class Tax implements CreatableFromArray
{
    private float $totalForPayment;
    private float $total;
    private float $tax;
    private float $debt;
    private float $overpayment;
    private float $penalty;
    private float $nominalTax;
    private float $nominalOverpayment;
    private int $taxPeriodId;
    private ?float $lastPaymentAmount;
    private ?\DateTimeImmutable $lastPaymentDate;
    private array $regions;

    private function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $model = new self();

        $model->totalForPayment = $data['totalForPayment'];
        $model->total = $data['total'];
        $model->tax = $data['tax'];
        $model->debt = $data['debt'];
        $model->overpayment = $data['overpayment'];
        $model->penalty = $data['penalty'];
        $model->nominalTax = $data['nominalTax'];
        $model->nominalOverpayment = $data['nominalOverpayment'];
        $model->taxPeriodId = $data['taxPeriodId'];
        $model->lastPaymentAmount = $data['lastPaymentAmount'];
        $model->lastPaymentDate = $data['lastPaymentDate'] ? new \DateTimeImmutable($data['lastPaymentDate']) : null;
        $model->regions = $data['regions'];

        return $model;
    }

    public function getTotalForPayment(): float
    {
        return $this->totalForPayment;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function getDebt(): float
    {
        return $this->debt;
    }

    public function getOverpayment(): float
    {
        return $this->overpayment;
    }

    public function getPenalty(): float
    {
        return $this->penalty;
    }

    public function getNominalTax(): float
    {
        return $this->nominalTax;
    }

    public function getNominalOverpayment(): float
    {
        return $this->nominalOverpayment;
    }

    public function getTaxPeriodId(): int
    {
        return $this->taxPeriodId;
    }

    public function getLastPaymentAmount(): ?float
    {
        return $this->lastPaymentAmount;
    }

    public function getLastPaymentDate(): ?\DateTimeImmutable
    {
        return $this->lastPaymentDate;
    }

    public function getRegions(): array
    {
        return $this->regions;
    }
}
