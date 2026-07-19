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

    /**
     * @return array<string, mixed>
     */
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
        $amount = \is_float($this->amount) ? (string)$this->amount : $this->amount;
        $quantity = \is_float($this->quantity) ? (string)$this->quantity : $this->quantity;

        return BigDecimal::of($amount)->multipliedBy($quantity);
    }
}
