<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class DeviceIdGenerator
{
    private int $length;
    private bool $lowercased;
    private IdStrategyInterface $strategy;

    public function __construct(IdStrategyInterface $strategy = null, $length = 21, $lowercased = true)
    {
        $this->length = $length;
        $this->lowercased = $lowercased;
        $this->strategy = $strategy ?? new PlatformIdStrategy();
    }

    public function generate(): string
    {
        $generated = \substr(
            \str_replace(['+', '/', '='], '', base64_encode($this->strategy->getId())),
            0,
            $this->length
        );
        if ($this->lowercased) {
            $generated = \mb_strtolower($generated);
        }

        return $generated;
    }
}
