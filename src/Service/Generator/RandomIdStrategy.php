<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

final class RandomIdStrategy implements IdStrategyInterface
{
    private int $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    public function getId(): string
    {
        return random_bytes($this->length);
    }
}
