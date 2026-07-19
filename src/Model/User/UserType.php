<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\User;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-type UserTypeData array{
 *     id: int,
 *     lastName: null|string,
 *     displayName: string,
 *     middleName: null|string,
 *     email?: null|string,
 *     phone: string,
 *     inn: string,
 *     snils: null|string,
 *     avatarExists: bool,
 *     initialRegistrationDate: null|string,
 *     registrationDate: null|string,
 *     firstReceiptRegisterTime: null|string,
 *     firstReceiptCancelTime: null|string,
 *     hideCancelledReceipt: bool,
 *     registerAvailable: mixed,
 *     status: null|string,
 *     restrictedMode: bool,
 *     pfrUrl: null|string,
 *     login: null|string,
 * }
 */
final readonly class UserType implements CreatableFromArray
{
    public ?string $lastName;
    public int $id;
    public string $displayName;
    public ?string $middleName;
    public ?string $email;
    public string $phone;
    public string $inn;
    public ?string $snils;
    public bool $avatarExists;
    public ?\DateTimeInterface $initialRegistrationDate;
    public ?\DateTimeInterface $registrationDate;
    public ?\DateTimeInterface $firstReceiptRegisterTime;
    public ?\DateTimeInterface $firstReceiptCancelTime;
    public bool $hideCancelledReceipt;
    public mixed $registerAvailable;
    public ?string $status;
    public bool $restrictedMode;
    public ?string $pfrUrl;
    public ?string $login;

    /**
     * @param UserTypeData $data
     */
    final private function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->lastName = $data['lastName'];
        $this->displayName = $data['displayName'];
        $this->middleName = $data['middleName'];
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'];
        $this->inn = $data['inn'];
        $this->snils = $data['snils'];
        $this->avatarExists = $data['avatarExists'];
        $this->initialRegistrationDate = $data['initialRegistrationDate'] !== null
            ? new \DateTimeImmutable($data['initialRegistrationDate'])
            : null;
        $this->registrationDate = $data['registrationDate'] !== null
            ? new \DateTimeImmutable($data['registrationDate'])
            : null;
        $this->firstReceiptRegisterTime = $data['firstReceiptRegisterTime'] !== null
            ? new \DateTimeImmutable($data['firstReceiptRegisterTime'])
            : null;
        $this->firstReceiptCancelTime = $data['firstReceiptCancelTime'] !== null
            ? new \DateTimeImmutable($data['firstReceiptCancelTime'])
            : null;
        $this->hideCancelledReceipt = $data['hideCancelledReceipt'];
        $this->registerAvailable = $data['registerAvailable'];
        $this->status = $data['status'];
        $this->restrictedMode = $data['restrictedMode'];
        $this->pfrUrl = $data['pfrUrl'];
        $this->login = $data['login'];
    }

    /**
     * @param UserTypeData $data
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
