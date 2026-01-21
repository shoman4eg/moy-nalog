<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class IncomeListContentItem implements CreatableFromArray
{
    private string $approvedReceiptUuid;
    private string $name;
    private array $services;
    private \DateTimeInterface $operationTime;
    private \DateTimeInterface $requestTime;
    private \DateTimeInterface $registerTime;
    private int $taxPeriodId;
    private string $paymentType;
    private string $incomeType;
    private ?string $partnerCode;
    private float $totalAmount;
    private ?CancellationInfoType $cancellationInfo;
    private ?string $sourceDeviceId;
    private ?string $clientInn;
    private ?string $clientDisplayName;
    private ?string $partnerDisplayName;
    private ?string $partnerLogo;
    private ?string $partnerInn;
    private string $inn;
    private string $profession;
    private array $description;
    private ?string $invoiceId;

    private function __construct() {}

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $services = array_map(
            static fn (array $service) => ServiceItemType::fromArray($service),
            $data['services'] ?? []
        );

        $cancellationInfo = null;
        if ($data['cancellationInfo'] !== null) {
            $cancellationInfo = CancellationInfoType::createFromArray($data['cancellationInfo']);
        }

        $model = new self();
        $model->approvedReceiptUuid = $data['approvedReceiptUuid'];
        $model->name = $data['name'];
        $model->services = $services;
        $model->operationTime = new \DateTimeImmutable($data['operationTime']);
        $model->requestTime = new \DateTimeImmutable($data['requestTime']);
        $model->registerTime = new \DateTimeImmutable($data['registerTime']);
        $model->taxPeriodId = $data['taxPeriodId'];
        $model->paymentType = $data['paymentType'];
        $model->incomeType = $data['incomeType'];
        $model->partnerCode = $data['partnerCode'];
        $model->totalAmount = (float)$data['totalAmount'];
        $model->cancellationInfo = $cancellationInfo;
        $model->sourceDeviceId = $data['sourceDeviceId'];
        $model->clientInn = $data['clientInn'];
        $model->clientDisplayName = $data['clientDisplayName'];
        $model->partnerDisplayName = $data['partnerDisplayName'];
        $model->partnerLogo = $data['partnerLogo'];
        $model->partnerInn = $data['partnerInn'];
        $model->inn = $data['inn'];
        $model->profession = $data['profession'];
        $model->description = $data['description'] ?? [];
        $model->invoiceId = $data['invoiceId'];

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

    /**
     * @return ServiceItemType[]
     */
    public function getServices(): array
    {
        return $this->services;
    }

    public function getOperationTime(): \DateTimeInterface
    {
        return $this->operationTime;
    }

    public function getRequestTime(): \DateTimeInterface
    {
        return $this->requestTime;
    }

    public function getRegisterTime(): \DateTimeInterface
    {
        return $this->registerTime;
    }

    public function getTaxPeriodId(): int
    {
        return $this->taxPeriodId;
    }

    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    public function getIncomeType(): string
    {
        return $this->incomeType;
    }

    public function getPartnerCode(): ?string
    {
        return $this->partnerCode;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getCancellationInfo(): ?CancellationInfoType
    {
        return $this->cancellationInfo;
    }

    public function isCancelled(): bool
    {
        return $this->cancellationInfo !== null;
    }

    public function getSourceDeviceId(): ?string
    {
        return $this->sourceDeviceId;
    }

    public function getClientInn(): ?string
    {
        return $this->clientInn;
    }

    public function getClientDisplayName(): ?string
    {
        return $this->clientDisplayName;
    }

    public function getPartnerDisplayName(): ?string
    {
        return $this->partnerDisplayName;
    }

    public function getPartnerLogo(): ?string
    {
        return $this->partnerLogo;
    }

    public function getPartnerInn(): ?string
    {
        return $this->partnerInn;
    }

    public function getInn(): string
    {
        return $this->inn;
    }

    public function getProfession(): string
    {
        return $this->profession;
    }

    /**
     * @return string[]
     */
    public function getDescription(): array
    {
        return $this->description;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoiceId;
    }
}
