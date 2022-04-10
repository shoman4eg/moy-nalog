<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Enum;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class CancelCommentType
{
    public const CANCEL = 'Чек сформирован ошибочно';
    public const REFUND = 'Возврат средств';

    public static function all(): array
    {
        return [
            self::CANCEL,
            self::REFUND,
        ];
    }
}
