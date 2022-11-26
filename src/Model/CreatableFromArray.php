<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface CreatableFromArray
{
    /**
     * Create an API response object from the HTTP response from the API server.
     */
    public static function createFromArray(array $data): self;
}
