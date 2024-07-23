<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\Exception\Domain\UnauthorizedException;
use Shoman4eg\Nalog\Exception\DomainException;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 *
 * @coversNothing
 */
final class ApiClientTest extends ApiTestCase
{
    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
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
