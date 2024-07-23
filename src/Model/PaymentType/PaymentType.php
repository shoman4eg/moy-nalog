<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\PaymentType;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class PaymentType implements CreatableFromArray
{
    private int $id;
    private string $type;
    private string $bankName;
    private string $bankBik;
    private string $corrAccount;
    private bool $favorite;
    private ?string $phone;
    private ?string $bankId;
    private string $currentAccount;
    private bool $availableForPa;

    private function __construct() {}

    public static function createFromArray(array $data): self
    {
        $model = new self();

        $model->id = $data['id'];
        $model->type = $data['type'];
        $model->bankName = $data['bankName'];
        $model->bankBik = $data['bankBik'];
        $model->currentAccount = $data['currentAccount'];
        $model->corrAccount = $data['corrAccount'];
        $model->phone = $data['phone'];
        $model->bankId = $data['bankId'];
        $model->favorite = $data['favorite'];
        $model->availableForPa = $data['availableForPa'];

        return $model;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBankName(): string
    {
        return $this->bankName;
    }

    public function getCorrAccount(): string
    {
        return $this->corrAccount;
    }

    public function isFavorite(): bool
    {
        return $this->favorite;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getBankId(): ?string
    {
        return $this->bankId;
    }

    public function getCurrentAccount(): string
    {
        return $this->currentAccount;
    }

    public function isAvailableForPa(): bool
    {
        return $this->availableForPa;
    }

    public function getBankBik(): string
    {
        return $this->bankBik;
    }
}
