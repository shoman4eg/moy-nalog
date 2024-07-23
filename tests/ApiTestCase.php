<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Shoman4eg\Nalog\ApiClient;
use Shoman4eg\Nalog\Util\JSON;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 *
 * @coversNothing
 */
abstract class ApiTestCase extends TestCase
{
    protected MockHandler $mock;
    protected ApiClient $client;

    protected function setUp(): void
    {
        $this->mock = new MockHandler();
        $this->client = ApiClient::createWithCustomClient(new Client(['handler' => new HandlerStack($this->mock)]));
        $this->client->authenticate(self::getAccessToken());
    }

    public static function getAccessToken(): string
    {
        return JSON::encode([
            'refreshToken' => 'dasdasdas',
            'refreshTokenExpiresIn' => null,
            'token' => 'randomString',
            'tokenExpireIn' => '2022-02-01T00:47:30.446Z',
            'profile' => [
                'lastName' => null,
                'id' => 1000000,
                'displayName' => 'displayName',
                'middleName' => null,
                'email' => 'email@example.com',
                'phone' => '79000000000',
                'inn' => '3000000000000',
                'snils' => '000-000-000 00',
                'avatarExists' => false,
                'initialRegistrationDate' => '2021-01-27T22:38:30.057957Z',
                'registrationDate' => '2021-01-27T22:38:30.057957Z',
                'firstReceiptRegisterTime' => '2021-03-11T13:37:23Z',
                'firstReceiptCancelTime' => null,
                'hideCancelledReceipt' => false,
                'registerAvailable' => null,
                'status' => 'ACTIVE',
                'restrictedMode' => false,
                'pfrUrl' => null,
                'login' => null,
            ],
        ]);
    }

    /**
     * @throws \JsonException
     */
    protected function appendSuccessJson(array $data): void
    {
        $this->appendSuccessJsonString(JSON::encode($data));
    }

    protected function appendSuccessJsonString(string $data): void
    {
        $this->mock->append(new Response(200, ['Content-Type' => 'application/json'], $data));
    }
}
