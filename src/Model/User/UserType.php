<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\User;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class UserType implements CreatableFromArray
{
    private ?string $lastName;
    private int $id;
    private string $displayName;
    private ?string $middleName;
    private ?string $email;
    private string $phone;
    private string $inn;
    private ?string $snils;
    private bool $avatarExists;
    private ?\DateTimeInterface $initialRegistrationDate;
    private ?\DateTimeInterface $registrationDate;
    private ?\DateTimeInterface $firstReceiptRegisterTime;
    private ?\DateTimeInterface $firstReceiptCancelTime;
    private bool $hideCancelledReceipt;

    /** @var mixed */
    private $registerAvailable;

    private ?string $status;
    private bool $restrictedMode;
    private ?string $pfrUrl;
    private ?string $login;

    final private function __construct() {}

    public static function createFromArray(array $data): self
    {
        $model = new self();
        $model->id = $data['id'];
        $model->lastName = $data['lastName'];
        $model->displayName = $data['displayName'];
        $model->middleName = $data['middleName'];
        $model->email = $data['email'] ?? null;
        $model->phone = $data['phone'];
        $model->inn = $data['inn'];
        $model->snils = $data['snils'];
        $model->avatarExists = $data['avatarExists'];
        $model->initialRegistrationDate = $data['initialRegistrationDate'] !== null
            ? new \DateTimeImmutable($data['initialRegistrationDate'])
            : null;
        $model->registrationDate = $data['registrationDate'] !== null
            ? new \DateTimeImmutable($data['registrationDate'])
            : null;
        $model->firstReceiptRegisterTime = $data['firstReceiptRegisterTime'] !== null
            ? new \DateTimeImmutable($data['firstReceiptRegisterTime'])
            : null;
        $model->firstReceiptCancelTime = $data['firstReceiptCancelTime'] !== null
            ? new \DateTimeImmutable($data['firstReceiptCancelTime'])
            : null;
        $model->hideCancelledReceipt = $data['hideCancelledReceipt'];
        $model->registerAvailable = $data['registerAvailable'];
        $model->status = $data['status'];
        $model->restrictedMode = $data['restrictedMode'];
        $model->pfrUrl = $data['pfrUrl'];
        $model->login = $data['login'];

        return $model;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInn(): string
    {
        return $this->inn;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function isHideCancelledReceipt(): bool
    {
        return $this->hideCancelledReceipt;
    }

    /**
     * @return mixed
     */
    public function getRegisterAvailable()
    {
        return $this->registerAvailable;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function isAvatarExists(): bool
    {
        return $this->avatarExists;
    }

    public function getInitialRegistrationDate(): ?\DateTimeInterface
    {
        return $this->initialRegistrationDate;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function getFirstReceiptRegisterTime(): ?\DateTimeInterface
    {
        return $this->firstReceiptRegisterTime;
    }

    public function getFirstReceiptCancelTime(): ?\DateTimeInterface
    {
        return $this->firstReceiptCancelTime;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function isRestrictedMode(): bool
    {
        return $this->restrictedMode;
    }

    public function getPfrUrl(): ?string
    {
        return $this->pfrUrl;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getSnils(): ?string
    {
        return $this->snils;
    }
}
