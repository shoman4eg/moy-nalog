<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Enum;

enum BuyerType: string implements \JsonSerializable
{
    case PERSON = 'PERSON';
    case COMPANY = 'COMPANY';
    case FOREIGN_AGENCY = 'FOREIGN_AGENCY';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
