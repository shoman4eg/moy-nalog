<?php

declare(strict_types=1);

namespace Shoman4eg\Nalog;

use Http\Client\HttpClient;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Shoman4eg\Nalog\Http\AuthenticationPlugin;
use Shoman4eg\Nalog\Http\Authenticator;
use Shoman4eg\Nalog\Http\ClientConfigurator;
use Shoman4eg\Nalog\Model\User\UserType;
use Shoman4eg\Nalog\Service\Generator\DeviceIdGenerator;
use Shoman4eg\Nalog\Util\Json;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
class ApiClient
{
    private RequestBuilder $requestBuilder;
    private ClientConfigurator $clientConfigurator;
    private Authenticator $authenticator;
    private ?UserType $profile;

    /**
     * The constructor accepts already configured HTTP clients.
     * Use the configure method to pass a configuration to the Client and create an HTTP Client.
     */
    public function __construct(
        ClientConfigurator $clientConfigurator,
        RequestBuilder $requestBuilder = null,
        DeviceIdGenerator $deviceIdGenerator = null
    ) {
        $this->clientConfigurator = $clientConfigurator;
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $deviceIdGenerator ??= new DeviceIdGenerator();
        $this->authenticator = new Authenticator(
            $this->requestBuilder,
            $this->clientConfigurator->createConfiguredClient(),
            $deviceIdGenerator->generate()
        );
    }

    public static function create(): self
    {
        $clientConfigurator = new ClientConfigurator();

        return new self($clientConfigurator);
    }

    public static function createWithEndpoint(string $endpoint): self
    {
        $clientConfigurator = new ClientConfigurator();
        $clientConfigurator->setEndpoint($endpoint);

        return new self($clientConfigurator);
    }

    public static function createWithCustomClient(ClientInterface $client): self
    {
        return new self(new ClientConfigurator($client));
    }

    /**
     * Warning, this will remove the current access token.
     *
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function createNewAccessToken(string $username, string $password): ?string
    {
        $this->clientConfigurator->removePlugin(AuthenticationPlugin::class);

        return $this->authenticator->createAccessToken($username, $password);
    }

    /**
     * Authenticate the client with an access token. This should be the full access token object with
     * refresh token and expirery timestamps.
     *
     * ```php
     *   $accessToken = $client->createNewAccessToken('inn', 'password');
     *   $client->authenticate($accessToken);
     *```
     *
     * @throws JsonException
     */
    public function authenticate(string $accessToken): void
    {
        $this->clientConfigurator->removePlugin(AuthenticationPlugin::class);
        $this->clientConfigurator->appendPlugin(new AuthenticationPlugin($this->authenticator, $accessToken));
        if (($token = Json::decode($accessToken)) && array_key_exists('profile', $token)) {
            $this->profile = UserType::createFromArray($token['profile']);
        }
        $this->authenticator->setAccessToken($accessToken);
    }

    /**
     * The access token may have been refreshed during the requests. Use this function to
     * get back the (possibly) refreshed access token.
     */
    public function getAccessToken(): ?string
    {
        return $this->authenticator->getAccessToken();
    }

    public function income(): Api\Income
    {
        return new Api\Income($this->getHttpClient(), $this->requestBuilder);
    }

    public function receipt(): Api\Receipt
    {
        return new Api\Receipt(
            $this->getHttpClient(),
            $this->requestBuilder,
            $this->profile ?? $this->user()->get(),
            $this->clientConfigurator->getEndpoint()
        );
    }

    public function user(): Api\User
    {
        return new Api\User($this->getHttpClient(), $this->requestBuilder);
    }

    public function paymentType(): Api\PaymentType
    {
        return new Api\PaymentType($this->getHttpClient(), $this->requestBuilder);
    }

    private function getHttpClient(): HttpClient
    {
        return $this->clientConfigurator->createConfiguredClient();
    }
}
