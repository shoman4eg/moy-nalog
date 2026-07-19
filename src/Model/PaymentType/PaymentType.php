<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\PaymentType;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-type PaymentTypeData array{
 *     id: int,
 *     type: string,
 *     bankName: string,
 *     bankBik: string,
 *     currentAccount: string,
 *     corrAccount: string,
 *     phone: string|null,
 *     bankId: string|null,
 *     favorite: bool,
 *     availableForPa: bool,
 * }
 */
final readonly class PaymentType implements CreatableFromArray
{
    public int $id;
    public string $type;
    public string $bankName;
    public string $bankBik;
    public string $corrAccount;
    public bool $favorite;
    public ?string $phone;
    public ?string $bankId;
    public string $currentAccount;
    public bool $availableForPa;

    /**
     * @param PaymentTypeData $data
     */
    private function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->type = $data['type'];
        $this->bankName = $data['bankName'];
        $this->bankBik = $data['bankBik'];
        $this->currentAccount = $data['currentAccount'];
        $this->corrAccount = $data['corrAccount'];
        $this->phone = $data['phone'];
        $this->bankId = $data['bankId'];
        $this->favorite = $data['favorite'];
        $this->availableForPa = $data['availableForPa'];
    }

    /**
     * @param PaymentTypeData $data
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
