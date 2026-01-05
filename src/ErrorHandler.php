<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog;

use Psr\Http\Message\ResponseInterface;
use Shoman4eg\Nalog\Exception\Domain as DomainExceptions;
use Shoman4eg\Nalog\Exception\DomainException;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class ErrorHandler
{
    /**
     * Handle HTTP errors.
     *
     * Call is controlled by the specific API methods.
     *
     * @throws DomainException
     */
    public function handleResponse(ResponseInterface $response): void
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
