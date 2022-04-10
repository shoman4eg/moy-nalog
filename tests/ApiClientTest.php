<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Shoman4eg\Nalog\ApiClient;

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
     */
    public function testCreateNewAccessToken(): void
    {
        $mock = new MockHandler([
            new Response(200, [], self::getAccessToken()),
            new Response(400, []),
        ]);
        $client = ApiClient::createWithCustomClient(new Client(['handler' => HandlerStack::create($mock)]));

        self::assertJson($client->createNewAccessToken('validUserName', 'validPassword'));
        self::assertNull($client->createNewAccessToken('invalidUserName', 'invalidPassword'));
    }

    public function testGetAccessToken(): void
    {
        $client = ApiClient::create();
        $client->authenticate(self::getAccessToken());
        self::assertJson($client->getAccessToken());
    }
}
