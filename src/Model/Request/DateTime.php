<?php

namespace Shoman4eg\Nalog\Model\Request;

class DateTime implements \JsonSerializable
{
    private \DateTimeInterface $dateTime;

    public function __construct(\DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function jsonSerialize()
    {
        return $this->dateTime->format(DATE_ATOM);
    }
}
