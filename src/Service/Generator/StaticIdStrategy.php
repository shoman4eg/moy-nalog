<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

final readonly class StaticIdStrategy implements IdStrategyInterface
{
    public function __construct(private int $id)
    {
    }

    public function getId(): string
    {
        return md5((string) $this->id);
    }
}
