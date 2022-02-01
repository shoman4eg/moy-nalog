<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Generator;

interface IdStrategyInterface
{
    public function getId(): string;
}
