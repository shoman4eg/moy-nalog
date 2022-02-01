<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Http;

use Http\Client\HttpClient;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\Model\Request\DeviceInfo;
use Shoman4eg\Nalog\RequestBuilder;
use Shoman4eg\Nalog\Utils\Json;

/**
 * Helper class to get access tokens.
 *
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal this class should not be used outside of the API Client, it is not part of the BC promise
 */
final class Authenticator
{
    private RequestBuilder $requestBuilder;
    private HttpClient $httpClient;
    private ?string $accessToken;
    private string $deviceId;

    public function __construct(RequestBuilder $requestBuilder, HttpClient $httpClient, string $deviceId)
    {
        $this->requestBuilder = $requestBuilder;
        $this->httpClient = $httpClient;
        $this->deviceId = $deviceId;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function createAccessToken(string $username, string $password): ?string
    {
        $request = $this->requestBuilder->create('POST', '/auth/lkfl', [
            'Referrer' => 'https://lknpd.nalog.ru/',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
        ], Json::encode([
            'username' => $username,
            'password' => $password,
            'deviceInfo' => new DeviceInfo($this->deviceId),
        ]));

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $this->accessToken = (string)$response->getBody();

        return $this->accessToken;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function refreshAccessToken(string $refreshToken): ?string
    {
        $request = $this->requestBuilder->create('POST', '/auth/token', [
            'Referrer' => 'https://lknpd.nalog.ru/sales',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
        ], Json::encode([
            'deviceInfo' => new DeviceInfo($this->deviceId),
            'refreshToken' => $refreshToken,
        ]));

        $response = $this->httpClient->sendRequest($request);
        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $this->accessToken = (string)$response->getBody();

        return $this->accessToken;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }
}
