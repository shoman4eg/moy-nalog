<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog;

use Psr\Http\Message\ResponseInterface;
use Shoman4eg\Nalog\Exception\Domain as DomainExceptions;
use Shoman4eg\Nalog\Exception\DomainException;

final class ErrorHandler
{
    /**
     * @throws DomainException
     */
    public function handleResponse(ResponseInterface $response): never
    {
        $body = (string)$response->getBody();

        throw match ($response->getStatusCode()) {
            400 => new DomainExceptions\ValidationException($body),
            401 => new DomainExceptions\UnauthorizedException($body),
            403 => new DomainExceptions\ForbiddenException(),
            404 => new DomainExceptions\NotFoundException(),
            406 => new DomainExceptions\ClientException('Wrong Accept headers'),
            422 => new DomainExceptions\PhoneException($body),
            500 => new DomainExceptions\ServerException(),
            default => new DomainExceptions\UnknownErrorException(),
        };
    }
}
