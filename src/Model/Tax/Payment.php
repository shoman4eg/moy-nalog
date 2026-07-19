<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Tax;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-type PaymentData array{
 *     sourceType: string,
 *     type: string,
 *     documentIndex: string,
 *     amount: float|int,
 *     operationDate: string,
 *     dueDate: string,
 *     oktmo: string,
 *     kbk: string,
 *     status: string,
 *     taxPeriodId: int,
 *     regionName: string,
 *     krsbAcceptedDate: string|null,
 * }
 */
final readonly class Payment implements CreatableFromArray
{
    public string $sourceType;
    public string $type;
    public string $documentIndex;
    public float $amount;
    public \DateTimeImmutable $operationDate;
    public \DateTimeImmutable $dueDate;
    public string $oktmo;
    public string $kbk;
    public string $status;
    public int $taxPeriodId;
    public string $regionName;
    public ?\DateTimeImmutable $krsbAcceptedDate;

    /**
     * @param PaymentData $data
     *
     * @throws \Exception
     */
    private function __construct(array $data)
    {
        $this->sourceType = $data['sourceType'];
        $this->type = $data['type'];
        $this->documentIndex = $data['documentIndex'];
        $this->amount = $data['amount'];
        $this->operationDate = new \DateTimeImmutable($data['operationDate']);
        $this->dueDate = new \DateTimeImmutable($data['dueDate']);
        $this->oktmo = $data['oktmo'];
        $this->kbk = $data['kbk'];
        $this->status = $data['status'];
        $this->taxPeriodId = $data['taxPeriodId'];
        $this->regionName = $data['regionName'];
        $this->krsbAcceptedDate = $data['krsbAcceptedDate'] ? new \DateTimeImmutable($data['krsbAcceptedDate']) : null;
    }

    /**
     * @param PaymentData $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
