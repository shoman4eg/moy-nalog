<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Enum;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class IncomeType
{
    public const INDIVIDUAL = 'FROM_INDIVIDUAL';
    public const LEGAL_ENTITY = 'FROM_LEGAL_ENTITY';
    public const FOREIGN_AGENCY = 'FROM_FOREIGN_AGENCY';

    public static function all(): array
    {
        return [
            self::INDIVIDUAL,
            self::LEGAL_ENTITY,
            self::FOREIGN_AGENCY,
        ];
    }
}
