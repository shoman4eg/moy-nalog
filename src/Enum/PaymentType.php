<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Enum;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
class PaymentType
{
    public const CASH = 'CASH';
    public const ACCOUNT = 'ACCOUNT';

    public static function all(): array
    {
        return [
            self::CASH,
            self::ACCOUNT,
        ];
    }
}
