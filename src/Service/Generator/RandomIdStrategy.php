<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class RandomIdStrategy implements IdStrategyInterface
{
    /**
     * @psalm-param positive-int $length
     */
    public function __construct(
        private int $length
    ) {}

    /**
     * @throws \Exception
     */
    public function getId(): string
    {
        return random_bytes($this->length);
    }
}
