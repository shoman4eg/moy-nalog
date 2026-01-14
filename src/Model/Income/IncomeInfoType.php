<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class IncomeInfoType implements CreatableFromArray
{
    private string $approvedReceiptUuid;
    private string $name;
    private \DateTimeImmutable $operationTime;
    private \DateTimeImmutable $requestTime;
    private string $paymentType;
    private ?string $partnerCode = null;
    private float $totalAmount;
    private CancellationInfoType $cancellationInfo;
    private string $sourceDeviceId;

    private function __construct() {}

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $model = new self();
        $data = $data['incomeInfo'];

        $model->approvedReceiptUuid = $data['approvedReceiptUuid'];
        $model->name = $data['name'];
        $model->operationTime = new \DateTimeImmutable($data['operationTime']);
        $model->requestTime = new \DateTimeImmutable($data['requestTime']);
        $model->paymentType = $data['paymentType'];
        $model->partnerCode = $data['partnerCode'];
        $model->totalAmount = $data['totalAmount'];
        $model->cancellationInfo = CancellationInfoType::createFromArray($data['cancellationInfo']);
        $model->sourceDeviceId = $data['sourceDeviceId'];

        return $model;
    }

    public function getApprovedReceiptUuid(): string
    {
        return $this->approvedReceiptUuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOperationTime(): \DateTimeImmutable
    {
        return $this->operationTime;
    }

    public function getRequestTime(): \DateTimeImmutable
    {
        return $this->requestTime;
    }

    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    public function getPartnerCode(): ?string
    {
        return $this->partnerCode;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getCancellationInfo(): CancellationInfoType
    {
        return $this->cancellationInfo;
    }

    public function getSourceDeviceId(): string
    {
        return $this->sourceDeviceId;
    }
}
