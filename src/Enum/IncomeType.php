<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Enum;

enum IncomeType: string implements \JsonSerializable
{
    case INDIVIDUAL = 'FROM_INDIVIDUAL';
    case LEGAL_ENTITY = 'FROM_LEGAL_ENTITY';
    case FOREIGN_AGENCY = 'FROM_FOREIGN_AGENCY';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
