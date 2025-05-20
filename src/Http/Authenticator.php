<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Http;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Shoman4eg\Nalog\DTO\DeviceInfo;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\RequestBuilder;
use Shoman4eg\Nalog\Util\JSON;

/**
 * Helper class to get access tokens.
 *
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal this class should not be used outside the API Client, it is not part of the BC promise
 */
final class Authenticator
{
    private RequestBuilder $requestBuilder;
    private ClientInterface $httpClient;
    private ?string $accessToken;
    private string $deviceId;
    private array $defaultHeaders = [
        'Referrer' => 'https://lknpd.nalog.ru/auth/login',
    ];

    public function __construct(RequestBuilder $requestBuilder, ClientInterface $httpClient, string $deviceId)
    {
        $this->requestBuilder = $requestBuilder;
        $this->httpClient = $httpClient;
        $this->deviceId = $deviceId;
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function createAccessToken(string $username, string $password): ?string
    {
        $request = $this->requestBuilder->create(
            'POST',
            '/auth/lkfl',
            $this->defaultHeaders,
            JSON::encode([
                'username' => $username,
                'password' => $password,
                'deviceInfo' => new DeviceInfo($this->deviceId),
            ])
        );

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        $this->accessToken = (string)$response->getBody();

        return $this->accessToken;
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     */
    public function createAccessTokenByPhone(string $phone, string $challengeToken, string $verificationCode): ?string
    {
        $request = $this->requestBuilder->create(
            'POST',
            '/auth/challenge/sms/verify',
            $this->defaultHeaders,
            JSON::encode([
                'phone' => $phone,
                'code' => $verificationCode,
                'challengeToken' => $challengeToken,
                'deviceInfo' => new DeviceInfo($this->deviceId),
            ])
        );

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        $this->accessToken = (string)$response->getBody();

        return $this->accessToken;
    }

    /**
     * @return array{challengeToken: string, expireDate: string, expireIn: int}
     *
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function createPhoneChallenge(string $phone): array
    {
        $request = $this->requestBuilder->create(
            'POST',
            '/auth/challenge/sms/start',
            $this->defaultHeaders,
            JSON::encode([
                'phone' => $phone,
                'requireTpToBeActive' => true,
            ])
        );

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        $response = (string)$response->getBody();

        return JSON::decode($response);
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     */
    public function refreshAccessToken(string $refreshToken): ?string
    {
        $request = $this->requestBuilder->create(
            'POST',
            '/auth/token',
            $this->defaultHeaders,
            JSON::encode([
                'deviceInfo' => new DeviceInfo($this->deviceId),
                'refreshToken' => $refreshToken,
            ])
        );

        $response = $this->httpClient->sendRequest($request);
        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $this->accessToken = (string)$response->getBody();

        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }
}
