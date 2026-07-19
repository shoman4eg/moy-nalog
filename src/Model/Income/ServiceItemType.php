<?php

declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

/**
 * @phpstan-type ServiceItemData array{
 *     name: string,
 *     quantity: float|int,
 *     serviceNumber: int,
 *     amount: float|int,
 * }
 */
final readonly class ServiceItemType
{
    public string $name;
    public float|int $quantity;
    public int $serviceNumber;
    public float|int $amount;

    /**
     * @param ServiceItemData $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->quantity = $data['quantity'];
        $this->serviceNumber = $data['serviceNumber'];
        $this->amount = $data['amount'];
    }

    /**
     * @param ServiceItemData $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
