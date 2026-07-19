<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-import-type CancellationInfoData from CancellationInfoType
 *
 * @phpstan-type CancelIncomeInfoData array{
 *     incomeInfo: array{
 *         approvedReceiptUuid: string,
 *         name: string,
 *         operationTime: string,
 *         requestTime: string,
 *         paymentType: string,
 *         partnerCode: null|string,
 *         totalAmount: float|int,
 *         cancellationInfo: CancellationInfoData,
 *         sourceDeviceId: string,
 *     },
 * }
 */
final readonly class CancelIncomeInfoType implements CreatableFromArray
{
    public string $approvedReceiptUuid;
    public string $name;
    public \DateTimeImmutable $operationTime;
    public \DateTimeImmutable $requestTime;
    public string $paymentType;
    public ?string $partnerCode;
    public float $totalAmount;
    public CancellationInfoType $cancellationInfo;
    public string $sourceDeviceId;

    /**
     * @param CancelIncomeInfoData $data
     *
     * @throws \Exception
     */
    private function __construct(array $data)
    {
        $data = $data['incomeInfo'];

        $this->approvedReceiptUuid = $data['approvedReceiptUuid'];
        $this->name = $data['name'];
        $this->operationTime = new \DateTimeImmutable($data['operationTime']);
        $this->requestTime = new \DateTimeImmutable($data['requestTime']);
        $this->paymentType = $data['paymentType'];
        $this->partnerCode = $data['partnerCode'];
        $this->totalAmount = $data['totalAmount'];
        $this->cancellationInfo = CancellationInfoType::createFromArray($data['cancellationInfo']);
        $this->sourceDeviceId = $data['sourceDeviceId'];
    }

    /**
     * @param CancelIncomeInfoData $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
