<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class DateTime implements \JsonSerializable
{
    private \DateTimeInterface $dateTime;

    public function __construct(\DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function jsonSerialize(): string
    {
        return $this->dateTime->format(DATE_ATOM);
    }
}
