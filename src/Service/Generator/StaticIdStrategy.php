<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class StaticIdStrategy implements IdStrategyInterface
{
    /**
     * @psalm-param non-empty-string $id
     */
    public function __construct(
        private string $id
    ) {}

    /**
     * @throws \Exception
     */
    public function getId(): string
    {
        return md5($this->id);
    }
}
