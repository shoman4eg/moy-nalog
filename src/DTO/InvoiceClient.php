<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

use Shoman4eg\Nalog\Enum\IncomeType;

final readonly class InvoiceClient implements \JsonSerializable
{
    public function __construct(
        public ?string    $contactPhone = null,
        public ?string    $displayName = null,
        public IncomeType $incomeType = IncomeType::INDIVIDUAL,
        public ?string    $inn = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'contactPhone' => $this->contactPhone,
            'displayName' => $this->displayName,
            'incomeType' => $this->incomeType->value,
            'inn' => $this->inn,
        ];
    }
}
