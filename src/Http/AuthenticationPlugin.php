<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Http;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shoman4eg\Nalog\Service\Util\JSON;

/**
 * This will automatically refresh expired access token.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class AuthenticationPlugin implements Plugin
{
    public const RETRY_LIMIT = 2;

    /** @var array<array-key, mixed> */
    private array $accessToken;

    /** @var array<string, int> */
    private array $retryStorage = [];

    public function __construct(private readonly Authenticator $authenticator, string $accessToken)
    {
        $decoded = JSON::decode($accessToken);
        $this->accessToken = \is_array($decoded) ? $decoded : [];
    }

    /**
     * @throws \JsonException
     * @throws \Throwable
     * @throws ClientExceptionInterface
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        if ($this->accessToken === [] || $request->hasHeader('Authorization')) {
            return $next($request);
        }

        $chainIdentifier = \spl_object_hash((object)$first);
        $token = $this->accessToken['token'] ?? '';
        $header = \sprintf('Bearer %s', \is_string($token) ? $token : '');
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

                $refreshToken = $this->accessToken['refreshToken'] ?? null;
                $accessToken = \is_string($refreshToken) ? $this->authenticator->refreshAccessToken($refreshToken) : null;
                if ($accessToken === null) {
                    return $response;
                }

                // Save new token
                $decoded = JSON::decode($accessToken);
                $this->accessToken = \is_array($decoded) ? $decoded : [];

                // Add new token to request
                $newToken = $this->accessToken['token'] ?? '';
                $header = \sprintf('Bearer %s', \is_string($newToken) ? $newToken : '');
                $request = $request->withHeader('Authorization', $header);

                // Retry
                ++$this->retryStorage[$chainIdentifier];

                return $this->handleRequest($request, $next, $first)->wait();
            }
        );
    }
}
