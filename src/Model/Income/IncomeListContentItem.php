<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-import-type ServiceItemData from ServiceItemType
 * @phpstan-import-type CancellationInfoData from CancellationInfoType
 *
 * @phpstan-type IncomeListContentItemData array{
 *     approvedReceiptUuid: string,
 *     name: string,
 *     services?: list<ServiceItemData>,
 *     operationTime: string,
 *     requestTime: string,
 *     registerTime: string,
 *     taxPeriodId: int,
 *     paymentType: string,
 *     incomeType: string,
 *     partnerCode: string|null,
 *     totalAmount: float|int,
 *     cancellationInfo: CancellationInfoData|null,
 *     sourceDeviceId: string|null,
 *     clientInn: string|null,
 *     clientDisplayName: string|null,
 *     partnerDisplayName: string|null,
 *     partnerLogo: string|null,
 *     partnerInn: string|null,
 *     inn: string,
 *     profession: string,
 *     description?: list<string>,
 *     invoiceId: string|null,
 * }
 */
final readonly class IncomeListContentItem implements CreatableFromArray
{
    public string $approvedReceiptUuid;
    public string $name;

    /** @var array<int, ServiceItemType> */
    public array $services;

    public \DateTimeInterface $operationTime;
    public \DateTimeInterface $requestTime;
    public \DateTimeInterface $registerTime;
    public int $taxPeriodId;
    public string $paymentType;
    public string $incomeType;
    public ?string $partnerCode;
    public float $totalAmount;
    public ?CancellationInfoType $cancellationInfo;
    public bool $cancelled;
    public ?string $sourceDeviceId;
    public ?string $clientInn;
    public ?string $clientDisplayName;
    public ?string $partnerDisplayName;
    public ?string $partnerLogo;
    public ?string $partnerInn;
    public string $inn;
    public string $profession;

    /** @var array<int, string> */
    public array $description;

    public ?string $invoiceId;

    /**
     * @param IncomeListContentItemData $data
     *
     * @throws \Exception
     */
    private function __construct(array $data)
    {
        $services = array_map(
            ServiceItemType::fromArray(...),
            $data['services'] ?? []
        );

        $cancellationInfo = null;
        if ($data['cancellationInfo'] !== null) {
            $cancellationInfo = CancellationInfoType::createFromArray($data['cancellationInfo']);
        }

        $this->approvedReceiptUuid = $data['approvedReceiptUuid'];
        $this->name = $data['name'];
        $this->services = $services;
        $this->operationTime = new \DateTimeImmutable($data['operationTime']);
        $this->requestTime = new \DateTimeImmutable($data['requestTime']);
        $this->registerTime = new \DateTimeImmutable($data['registerTime']);
        $this->taxPeriodId = $data['taxPeriodId'];
        $this->paymentType = $data['paymentType'];
        $this->incomeType = $data['incomeType'];
        $this->partnerCode = $data['partnerCode'];
        $this->totalAmount = (float)$data['totalAmount'];
        $this->cancellationInfo = $cancellationInfo;
        $this->cancelled = $cancellationInfo !== null;
        $this->sourceDeviceId = $data['sourceDeviceId'];
        $this->clientInn = $data['clientInn'];
        $this->clientDisplayName = $data['clientDisplayName'];
        $this->partnerDisplayName = $data['partnerDisplayName'];
        $this->partnerLogo = $data['partnerLogo'];
        $this->partnerInn = $data['partnerInn'];
        $this->inn = $data['inn'];
        $this->profession = $data['profession'];
        $this->description = $data['description'] ?? [];
        $this->invoiceId = $data['invoiceId'];
    }

    /**
     * @param IncomeListContentItemData $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
