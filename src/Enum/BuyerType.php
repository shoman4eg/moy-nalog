<?php

namespace Shoman4eg\Nalog\Enum;

class BuyerType
{
    public const PERSON = 'PERSON';
    public const COMPANY = 'COMPANY';
    public const FOREIGN_AGENCY = 'FOREIGN_AGENCY';

    public static function all(): array
    {
        return [
            self::PERSON,
            self::COMPANY,
            self::FOREIGN_AGENCY,
        ];
    }
}
