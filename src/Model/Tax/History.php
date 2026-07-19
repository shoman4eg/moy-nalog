<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Tax;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-type HistoryData array{
 *     taxPeriodId: int,
 *     taxAmount: float|int,
 *     bonusAmount: float|int,
 *     paidAmount: float|int,
 *     taxBaseAmount: float|int|null,
 *     chargeDate: string|null,
 *     dueDate: string|null,
 *     oktmo: string,
 *     regionName: string,
 *     kbk: string,
 *     taxOrganCode: string,
 *     type: string,
 *     krsbTaxChargeId: int,
 *     receiptCount: int,
 * }
 */
final readonly class History implements CreatableFromArray
{
    public int $taxPeriodId;
    public float $taxAmount;
    public float $bonusAmount;
    public float $paidAmount;
    public ?float $taxBaseAmount;
    public ?\DateTimeImmutable $chargeDate;
    public ?\DateTimeImmutable $dueDate;
    public string $oktmo;
    public string $regionName;
    public string $kbk;
    public string $taxOrganCode;
    public string $type;
    public int $krsbTaxChargeId;
    public int $receiptCount;

    /**
     * @param HistoryData $data
     *
     * @throws \Exception
     */
    private function __construct(array $data)
    {
        $this->taxPeriodId = $data['taxPeriodId'];
        $this->taxAmount = $data['taxAmount'];
        $this->bonusAmount = $data['bonusAmount'];
        $this->paidAmount = $data['paidAmount'];
        $this->taxBaseAmount = $data['taxBaseAmount'];
        $this->chargeDate = $data['chargeDate'] ? new \DateTimeImmutable($data['chargeDate']) : null;
        $this->dueDate = $data['dueDate'] ? new \DateTimeImmutable($data['dueDate']) : null;
        $this->oktmo = $data['oktmo'];
        $this->regionName = $data['regionName'];
        $this->kbk = $data['kbk'];
        $this->taxOrganCode = $data['taxOrganCode'];
        $this->type = $data['type'];
        $this->krsbTaxChargeId = $data['krsbTaxChargeId'];
        $this->receiptCount = $data['receiptCount'];
    }

    /**
     * @param HistoryData $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
