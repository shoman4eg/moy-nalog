<?php

namespace Shoman4eg\Nalog\Enum;

class ReceiptType
{
    public const REGISTERED = 'REGISTERED';
    public const CANCELLED = 'CANCELLED';

    public static function all(): array
    {
        return [
            self::REGISTERED,
            self::CANCELLED,
        ];
    }
}
