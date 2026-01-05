<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

use Brick\Math\BigDecimal;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class IncomeServiceItem implements \JsonSerializable
{
    public function __construct(
        private string $name,
        private float|int|string $amount,
        private float|int $quantity
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'amount' => $this->amount,
            'quantity' => $this->quantity,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float|int|string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return float|int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getTotalAmount(): BigDecimal
    {
        return BigDecimal::of($this->amount)
            ->multipliedBy($this->quantity)
        ;
    }
}
