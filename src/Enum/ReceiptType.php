<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Enum;

enum ReceiptType: string implements \JsonSerializable
{
    case REGISTERED = 'REGISTERED';
    case CANCELLED = 'CANCELLED';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
