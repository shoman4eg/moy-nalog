<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

use Shoman4eg\Nalog\Enum\IncomeType;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class InvoiceClient implements \JsonSerializable
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
}
