<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class InvoiceServiceItem implements \JsonSerializable
{
    private string $name;
    private $amount;
    private $quantity;
    private ?int $serviceNumber;

    /**
     * @param float|int|string $amount
     * @param float|int        $quantity
     * @param mixed            $serviceNumber
     */
    public function __construct(string $name, $amount, $quantity, $serviceNumber = 0)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->quantity = $quantity;
        $this->serviceNumber = $serviceNumber;
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'name' => $this->name,
            'amount' => $this->amount,
            'quantity' => $this->quantity,
            'serviceNumber' => $this->serviceNumber,
        ], static fn ($item) => $item !== null);
    }
}
