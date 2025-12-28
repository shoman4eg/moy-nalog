<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class StaticIdStrategy implements IdStrategyInterface
{
    /** @psalm-var non-empty-string $id */
    private string $id;

    /**
     * @psalm-param non-empty-string $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @throws \Exception
     */
    public function getId(): string
    {
        return md5($this->id);
    }
}
