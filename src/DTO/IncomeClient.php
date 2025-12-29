<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

use Shoman4eg\Nalog\Enum\IncomeType;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class IncomeClient implements \JsonSerializable
{
    public function __construct(
        private ?string $contactPhone = null,
        private ?string $displayName = null,
        private string $incomeType = IncomeType::INDIVIDUAL,
        private ?string $inn = null
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'contactPhone' => $this->contactPhone,
            'displayName' => $this->displayName,
            'incomeType' => $this->incomeType,
            'inn' => $this->inn,
        ];
    }

    public function getInn(): ?string
    {
        return $this->inn;
    }

    public function getIncomeType(): string
    {
        return $this->incomeType;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }
}
