<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Shoman4eg\Nalog\ApiClient;
use Shoman4eg\Nalog\Exception\Domain\UnauthorizedException;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 * @coversNothing
 */
class ApiClientTest extends ApiTestCase
{
    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \JsonException
     * @throws \Shoman4eg\Nalog\Exception\DomainException
     */
    public function testCreateNewAccessToken(): void
    {
        $this->mock->append(
            new Response(200, [], self::getAccessToken()),
            new Response(401, [], json_encode(['message' => 'Указанный Вами ИНН некорректен'])),
        );

        self::assertJson($this->client->createNewAccessToken('validUserName', 'validPassword'));
        $this->expectException(UnauthorizedException::class);
        $this->client->createNewAccessToken('invalidUserName', 'invalidPassword');
    }

    public function testGetAccessToken(): void
    {
        $this->client->authenticate(self::getAccessToken());
        self::assertJson($this->client->getAccessToken());
    }
}
