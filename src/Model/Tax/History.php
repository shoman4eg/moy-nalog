<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Tax;

use DateTimeImmutable;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class History implements CreatableFromArray
{
    private int $taxPeriodId;
    private float $taxAmount;
    private float $bonusAmount;
    private float $paidAmount;
    private ?float $taxBaseAmount;
    private ?DateTimeImmutable $chargeDate;
    private ?DateTimeImmutable $dueDate;
    private string $oktmo;
    private string $regionName;
    private string $kbk;
    private string $taxOrganCode;
    private string $type;
    private int $krsbTaxChargeId;
    private int $receiptCount;

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $model = new self();
        $model->taxPeriodId = $data['taxPeriodId'];
        $model->taxAmount = $data['taxAmount'];
        $model->bonusAmount = $data['bonusAmount'];
        $model->paidAmount = $data['paidAmount'];
        $model->taxBaseAmount = $data['taxBaseAmount'];
        $model->chargeDate = $data['chargeDate'] ? new DateTimeImmutable($data['chargeDate']) : null;
        $model->dueDate = $data['chargeDate'] ? new DateTimeImmutable($data['dueDate']) : null;
        $model->oktmo = $data['oktmo'];
        $model->regionName = $data['regionName'];
        $model->kbk = $data['kbk'];
        $model->taxOrganCode = $data['taxOrganCode'];
        $model->type = $data['type'];
        $model->krsbTaxChargeId = $data['krsbTaxChargeId'];
        $model->receiptCount = $data['receiptCount'];

        return $model;
    }

    public function getPaidAmount(): float
    {
        return $this->paidAmount;
    }

    public function getBonusAmount(): float
    {
        return $this->bonusAmount;
    }

    public function getTaxAmount(): float
    {
        return $this->taxAmount;
    }

    public function getTaxPeriodId(): int
    {
        return $this->taxPeriodId;
    }

    public function getOktmo(): string
    {
        return $this->oktmo;
    }

    public function getRegionName(): string
    {
        return $this->regionName;
    }

    public function getKbk(): string
    {
        return $this->kbk;
    }

    public function getTaxOrganCode(): string
    {
        return $this->taxOrganCode;
    }

    public function getKrsbTaxChargeId(): int
    {
        return $this->krsbTaxChargeId;
    }

    public function getReceiptCount(): int
    {
        return $this->receiptCount;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getChargeDate(): ?DateTimeImmutable
    {
        return $this->chargeDate;
    }

    public function getDueDate(): ?DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function getTaxBaseAmount(): ?float
    {
        return $this->taxBaseAmount;
    }
}
