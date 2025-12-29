<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class DateTime implements \JsonSerializable
{
    public function __construct(private \DateTimeInterface $dateTime) {}

    public function jsonSerialize(): string
    {
        return $this->dateTime->format(DATE_ATOM);
    }
}
