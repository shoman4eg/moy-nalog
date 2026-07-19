<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Exception\Domain;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class UnauthorizedException extends ClientException
{
    public function __construct(string $message = '')
    {
        $decoded = json_decode($message, true);
        $text = \is_array($decoded) && isset($decoded['message']) && \is_string($decoded['message']) ? $decoded['message'] : '';
        parent::__construct($text);
    }
}
