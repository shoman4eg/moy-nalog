<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class PlatformIdStrategy implements IdStrategyInterface
{
    public function getId(): string
    {
        return sprintf('%s-%s', php_uname(), PHP_VERSION_ID);
    }
}
