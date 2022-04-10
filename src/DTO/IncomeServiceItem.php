<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

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
}
