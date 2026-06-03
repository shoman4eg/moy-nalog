<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

final readonly class RandomIdStrategy implements IdStrategyInterface
{
    public function __construct(private int $length) {}

    /**
     * @throws \Exception
     */
    public function getId(): string
    {
        return random_bytes($this->length);
    }
}
