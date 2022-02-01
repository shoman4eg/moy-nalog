<?php

namespace Shoman4eg\Nalog\Model\User;

use Shoman4eg\Nalog\Model\CreatableFromArray;

class UserType implements CreatableFromArray
{
    private ?string $lastName;
    private int $id;
    private string $displayName;
    private ?string $middleName;
    private string $email;
    private string $phone;
    private string $inn;
    private ?string $snils;
    private bool $avatarExists;
    private ?\DateTimeInterface $initialRegistrationDate;
    private ?\DateTimeInterface $registrationDate;
    private ?\DateTimeInterface $firstReceiptRegisterTime;
    private ?\DateTimeInterface $firstReceiptCancelTime;
    private bool $hideCancelledReceipt;
    private $registerAvailable;
    private $status;
    private bool $restrictedMode;
    private string $pfrUrl;
    private ?string $login;

    public static function createFromArray(array $data): self
    {
        $self = new self();
        $self->id = $data['id'];
        $self->lastName = $data['lastName'];
        $self->displayName = $data['displayName'];
        $self->middleName = $data['middleName'];
        $self->email = $data['email'];
        $self->phone = $data['phone'];
        $self->inn = $data['inn'];
        $self->snils = $data['snils'];
        $self->avatarExists = $data['avatarExists'];
        $self->initialRegistrationDate = $data['initialRegistrationDate'] !== null
            ? new \DateTimeImmutable($data['initialRegistrationDate'])
            : null;
        $self->registrationDate = $data['registrationDate'] !== null
            ? new \DateTimeImmutable($data['registrationDate'])
            : null;
        $self->firstReceiptRegisterTime = $data['firstReceiptRegisterTime'] !== null
            ? new \DateTimeImmutable($data['firstReceiptRegisterTime'])
            : null;
        $self->firstReceiptCancelTime = $data['firstReceiptCancelTime'] !== null
            ? new \DateTimeImmutable($data['firstReceiptCancelTime'])
            : null;
        $self->hideCancelledReceipt = $data['hideCancelledReceipt'];
        $self->registerAvailable = $data['registerAvailable'];
        $self->status = $data['status'];
        $self->restrictedMode = $data['restrictedMode'];
        $self->pfrUrl = $data['pfrUrl'];
        $self->login = $data['login'];

        return $self;
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

    public function getEmail(): string
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

    public function getPfrUrl(): string
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
