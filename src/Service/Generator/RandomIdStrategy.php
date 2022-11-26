<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class RandomIdStrategy implements IdStrategyInterface
{
    /** @psalm-var positive-int $length */
    private int $length;

    /**
     * @psalm-param positive-int $length
     */
    public function __construct(int $length)
    {
        $this->length = $length;
    }

    /**
     * @throws \Exception
     */
    public function getId(): string
    {
        return random_bytes($this->length);
    }
}
