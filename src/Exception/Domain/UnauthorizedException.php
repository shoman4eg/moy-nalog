<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Exception\Domain;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class UnauthorizedException extends ClientException
{
    public function __construct($message)
    {
        $decodedMessage = json_decode($message, true);
        parent::__construct($decodedMessage['message']);
    }
}
