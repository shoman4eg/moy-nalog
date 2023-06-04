<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Http;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shoman4eg\Nalog\Util\JSON;

/**
 * This will automatically refresh expired access token.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class AuthenticationPlugin implements Plugin
{
    public const RETRY_LIMIT = 2;

    private array $accessToken;
    private array $retryStorage = [];
    private Authenticator $authenticator;

    public function __construct(Authenticator $authenticator, string $accessToken)
    {
        $this->authenticator = $authenticator;
        $this->accessToken = JSON::decode($accessToken);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws \Exception
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        if ($this->accessToken === [] || $request->hasHeader('Authorization')) {
            return $next($request);
        }

        $chainIdentifier = \spl_object_hash((object)$first);
        $header = \sprintf('Bearer %s', $this->accessToken['token'] ?? '');
        $request = $request->withHeader('Authorization', $header);

        return $next($request)->then(
            function (ResponseInterface $response) use ($request, $next, $first, $chainIdentifier) {
                if (!\array_key_exists($chainIdentifier, $this->retryStorage)) {
                    $this->retryStorage[$chainIdentifier] = 0;
                }

                if ($response->getStatusCode() !== 401 || $this->retryStorage[$chainIdentifier] >= self::RETRY_LIMIT) {
                    unset($this->retryStorage[$chainIdentifier]);

                    return $response;
                }

                $accessToken = $this->authenticator->refreshAccessToken($this->accessToken['refreshToken']);
                if ($accessToken === null) {
                    return $response;
                }

                // Save new token
                $this->accessToken = JSON::decode($accessToken);

                // Add new token to request
                $header = \sprintf('Bearer %s', $this->accessToken['token']);
                $request = $request->withHeader('Authorization', $header);

                // Retry
                ++$this->retryStorage[$chainIdentifier];

                return $this->handleRequest($request, $next, $first)->wait();
            }
        );
    }
}
