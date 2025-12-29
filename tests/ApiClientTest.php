<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\ApiClient;
use Shoman4eg\Nalog\Exception\Domain\UnauthorizedException;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\Util\JSON;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 */
#[CoversClass(ApiClient::class)]
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
            new Response(401, [], JSON::encode(['message' => 'Указанный Вами ИНН некорректен'])),
        );

        self::assertJson($this->client->createNewAccessToken('validUserName', 'validPassword'));
        $this->expectException(UnauthorizedException::class);
        $this->client->createNewAccessToken('invalidUserName', 'invalidPassword');
    }

    /**
     * @throws \JsonException
     */
    public function testGetAccessToken(): void
    {
        $this->client->authenticate(self::getAccessToken());
        self::assertJson($this->client->getAccessToken());
    }
}
