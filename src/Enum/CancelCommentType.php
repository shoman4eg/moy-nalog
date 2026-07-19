<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Enum;

enum CancelCommentType: string implements \JsonSerializable
{
    case CANCEL = 'Чек сформирован ошибочно';
    case REFUND = 'Возврат средств';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
