<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

use Brick\Math\BigDecimal;

final readonly class IncomeServiceItem implements \JsonSerializable
{
    public function __construct(
        public string $name,
        public float|int|string $amount,
        public float|int $quantity,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'amount' => $this->amount,
            'quantity' => $this->quantity,
        ];
    }

    public function getTotalAmount(): BigDecimal
    {
        return BigDecimal::of($this->amount)->multipliedBy($this->quantity);
    }
}
