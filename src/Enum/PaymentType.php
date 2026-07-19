<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Enum;

enum PaymentType: string implements \JsonSerializable
{
    case CASH = 'CASH';
    case ACCOUNT = 'ACCOUNT';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
