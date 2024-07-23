<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Shoman4eg\Nalog\Http\AuthenticationPlugin;
use Shoman4eg\Nalog\Http\Authenticator;
use Shoman4eg\Nalog\Http\ClientConfigurator;
use Shoman4eg\Nalog\Model\User\UserType;
use Shoman4eg\Nalog\Service\Generator\DeviceIdGenerator;
use Shoman4eg\Nalog\Util\JSON;

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
        ?RequestBuilder $requestBuilder = null,
        ?DeviceIdGenerator $deviceIdGenerator = null
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
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     */
    public function createNewAccessToken(string $username, string $password): ?string
    {
        $this->clientConfigurator->removePlugin(AuthenticationPlugin::class);

        return $this->authenticator->createAccessToken($username, $password);
    }

    /**
     * Used as the first step, before createPhoneChallenge().
     *
     * Request the verification code in the SMS-message for authorization by phone number.
     * Returns the call token and its lifetime.
     *
     * Save the phone number and call token. When the user specifies the code from the SMS,
     * pass them to createNewAccessTokenByPhone() along with the code from the SMS
     *
     * Remember that there are restrictions on sending SMS. The value seems to be dynamic,
     * approximately 1 message every 1-2 minutes.
     * The restriction is removed after a successful createNewAccessTokenByPhone()
     *
     * @return array{challengeToken: string, expireDate: string, expireIn: int}
     *
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     */
    public static function createPhoneChallenge(string $phone): array
    {
        $client = self::create();
        $client->clientConfigurator->setVersion('v2');
        $client->clientConfigurator->removePlugin(AuthenticationPlugin::class);

        return $client->authenticator->createPhoneChallenge($phone);
    }

    /**
     * Used as the second step, after createPhoneChallenge()
     * Warning, this will remove the current access token.
     *
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     */
    public function createNewAccessTokenByPhone(string $phone, string $challengeToken, string $verificationCode): ?string
    {
        $this->clientConfigurator->removePlugin(AuthenticationPlugin::class);

        return $this->authenticator->createAccessTokenByPhone($phone, $challengeToken, $verificationCode);
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
     * @throws \JsonException
     */
    public function authenticate(string $accessToken): void
    {
        $this->clientConfigurator->removePlugin(AuthenticationPlugin::class);
        $this->clientConfigurator->appendPlugin(new AuthenticationPlugin($this->authenticator, $accessToken));
        if (($token = JSON::decode($accessToken)) && array_key_exists('profile', $token)) {
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
            (string)$this->clientConfigurator->getEndpoint()
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

    public function tax(): Api\Tax
    {
        return new Api\Tax($this->getHttpClient(), $this->requestBuilder);
    }

    private function getHttpClient(): ClientInterface
    {
        return $this->clientConfigurator->createConfiguredClient();
    }
}
