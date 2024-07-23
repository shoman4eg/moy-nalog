<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Tax;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class Payment implements CreatableFromArray
{
    private string $sourceType;
    private string $type;
    private string $documentIndex;
    private float $amount;
    private \DateTimeImmutable $operationDate;
    private \DateTimeImmutable $dueDate;
    private string $oktmo;
    private string $kbk;
    private string $status;
    private int $taxPeriodId;
    private string $regionName;
    private ?\DateTimeImmutable $krsbAcceptedDate;

    private function __construct() {}

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $model = new self();
        $model->sourceType = $data['sourceType'];
        $model->type = $data['type'];
        $model->documentIndex = $data['documentIndex'];
        $model->amount = $data['amount'];
        $model->operationDate = new \DateTimeImmutable($data['operationDate']);
        $model->dueDate = new \DateTimeImmutable($data['dueDate']);
        $model->oktmo = $data['oktmo'];
        $model->kbk = $data['kbk'];
        $model->status = $data['status'];
        $model->taxPeriodId = $data['taxPeriodId'];
        $model->regionName = $data['regionName'];
        $model->krsbAcceptedDate = $data['krsbAcceptedDate'] ? new \DateTimeImmutable($data['krsbAcceptedDate']) : null;

        return $model;
    }

    public function getSourceType(): string
    {
        return $this->sourceType;
    }

    public function getKrsbAcceptedDate(): ?\DateTimeImmutable
    {
        return $this->krsbAcceptedDate;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDocumentIndex(): string
    {
        return $this->documentIndex;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getOperationDate(): \DateTimeImmutable
    {
        return $this->operationDate;
    }

    public function getDueDate(): \DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function getOktmo(): string
    {
        return $this->oktmo;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getKbk(): string
    {
        return $this->kbk;
    }

    public function getTaxPeriodId(): int
    {
        return $this->taxPeriodId;
    }

    public function getRegionName(): string
    {
        return $this->regionName;
    }
}
