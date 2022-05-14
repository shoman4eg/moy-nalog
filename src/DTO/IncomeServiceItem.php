<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

use Brick\Math\BigDecimal;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class IncomeServiceItem implements \JsonSerializable
{
    private string $name;
    private $amount;
    private $quantity;

    /**
     * @param float|int|string $amount
     * @param float|int        $quantity
     */
    public function __construct(string $name, $amount, $quantity)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->quantity = $quantity;
    }

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
