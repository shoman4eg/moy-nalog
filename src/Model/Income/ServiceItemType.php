<?php

declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

final class ServiceItemType
{
    private string $name;

    /** @var float|int */
    private $quantity;

    private int $serviceNumber;

    /** @var float|int */
    private $amount;

    public function __construct() {}

    public static function fromArray(array $data): self
    {
        $model = new self();
        $model->name = $data['name'];
        $model->quantity = $data['quantity'];
        $model->serviceNumber = $data['serviceNumber'];
        $model->amount = $data['amount'];

        return $model;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getServiceNumber(): int
    {
        return $this->serviceNumber;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
