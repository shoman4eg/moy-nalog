<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

use Shoman4eg\Nalog\Enum\IncomeType;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class IncomeClient implements \JsonSerializable
{
    private ?string $contactPhone;
    private ?string $displayName;
    private string $incomeType;
    private ?string $inn;

    public function __construct(
        ?string $contactPhone = null,
        ?string $displayName = null,
        string $incomeType = IncomeType::INDIVIDUAL,
        ?string $inn = null
    ) {
        $this->contactPhone = $contactPhone;
        $this->displayName = $displayName;
        $this->incomeType = $incomeType;
        $this->inn = $inn;
    }

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
}
