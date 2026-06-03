<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

final readonly class DeviceIdGenerator
{
    private IdStrategyInterface $strategy;

    public function __construct(
        ?IdStrategyInterface $strategy = null,
        private int $length = 21,
        private bool $lowercased = true,
    ) {
        $this->strategy = $strategy ?? new PlatformIdStrategy();
    }

    public function generate(): string
    {
        $generated = substr(
            str_replace(['+', '/', '='], '', base64_encode($this->strategy->getId())),
            0,
            $this->length
        );

        return $this->lowercased ? mb_strtolower($generated) : $generated;
    }
}
