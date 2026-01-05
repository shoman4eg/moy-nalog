<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class InvoiceServiceItem implements \JsonSerializable
{
    public function __construct(
        private string $name,
        private float|int|string $amount,
        private float|int $quantity,
        private ?int $serviceNumber = 0
    ) {}

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
