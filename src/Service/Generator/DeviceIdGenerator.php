<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class DeviceIdGenerator
{
    private const LENGTH = 21;

    public function __construct(
        private ?IdStrategyInterface $strategy = new PlatformIdStrategy()
    ) {}

    public function generate(): string
    {
        return \substr(
            \str_replace(['+', '/', '='], '', base64_encode($this->strategy->getId())),
            0,
            self::LENGTH
        );
    }
}
