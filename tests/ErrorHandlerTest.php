<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests;

use GuzzleHttp\Psr7\Response;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception\Domain\ClientException;
use Shoman4eg\Nalog\Exception\Domain\ForbiddenException;
use Shoman4eg\Nalog\Exception\Domain\NotFoundException;
use Shoman4eg\Nalog\Exception\Domain\PhoneException;
use Shoman4eg\Nalog\Exception\Domain\ServerException;
use Shoman4eg\Nalog\Exception\Domain\UnauthorizedException;
use Shoman4eg\Nalog\Exception\Domain\UnknownErrorException;
use Shoman4eg\Nalog\Exception\Domain\ValidationException;
use Shoman4eg\Nalog\Service\Util\JSON;
use Testo\Data\DataProvider;
use Testo\Expect;
use Testo\Test;

#[Test]
final class ErrorHandlerTest
{
    #[DataProvider('statusCodeProvider')]
    public function testHandleResponseMapsStatusCodeToException(int $statusCode, string $expectedException): never
    {
        Expect::exception($expectedException);

        (new ErrorHandler())->handleResponse(new Response($statusCode, [], '{}'));
    }

    /**
     * @return iterable<string, array{int, class-string}>
     */
    public static function statusCodeProvider(): iterable
    {
        yield '400 validation' => [400, ValidationException::class];
        yield '401 unauthorized' => [401, UnauthorizedException::class];
        yield '403 forbidden' => [403, ForbiddenException::class];
        yield '404 not found' => [404, NotFoundException::class];
        yield '406 client' => [406, ClientException::class];
        yield '422 phone' => [422, PhoneException::class];
        yield '500 server' => [500, ServerException::class];
        yield 'unknown status falls back' => [418, UnknownErrorException::class];
    }

    public function testUnauthorizedExceptionExtractsApiMessage(): never
    {
        Expect::exception(UnauthorizedException::class)->withMessage('Неверный логин или пароль');

        (new ErrorHandler())->handleResponse(
            new Response(401, [], JSON::encode(['message' => 'Неверный логин или пароль']))
        );
    }

    public function testUnauthorizedExceptionWithoutMessageIsEmpty(): never
    {
        Expect::exception(UnauthorizedException::class)->withMessage('');

        (new ErrorHandler())->handleResponse(new Response(401, [], 'not-json'));
    }
}
