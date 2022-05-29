<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Http\Client\HttpClient;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\RequestBuilder;
use Shoman4eg\Nalog\Util\JSON;
use Shoman4eg\Nalog\Util\ModelHydrator;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class BaseHttpApi
{
    protected HttpClient $httpClient;
    protected RequestBuilder $requestBuilder;
    protected ModelHydrator $hydrator;

    public function __construct(HttpClient $httpClient, RequestBuilder $requestBuilder)
    {
        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder;
        $this->hydrator = new ModelHydrator();
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path           Request path
     * @param array  $params         GET parameters
     * @param array  $requestHeaders Request Headers
     *
     * @throws ClientExceptionInterface
     */
    protected function httpGet(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        if (\count($params) > 0) {
            $path .= sprintf('?%s', \http_build_query($params));
        }

        return $this->httpClient->sendRequest(
            $this->requestBuilder->create('GET', $path, $requestHeaders)
        );
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $requestHeaders Request headers
     *
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    protected function httpPost(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpPostRaw($path, $this->createJsonBody($params), $requestHeaders);
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string       $path           Request path
     * @param array|string $body           Request body
     * @param array        $requestHeaders Request headers
     *
     * @throws ClientExceptionInterface
     */
    protected function httpPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->requestBuilder->create('POST', $path, $requestHeaders, $body)
        );
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $requestHeaders Request headers
     *
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    protected function httpPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->requestBuilder->create('PUT', $path, $requestHeaders, $this->createJsonBody($params))
        );
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $requestHeaders Request headers
     *
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    protected function httpPatch(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->requestBuilder->create('PATCH', $path, $requestHeaders, $this->createJsonBody($params))
        );
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $requestHeaders Request headers
     *
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    protected function httpDelete(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->requestBuilder->create('DELETE', $path, $requestHeaders, $this->createJsonBody($params))
        );
    }

    /**
     * Handle HTTP errors.
     *
     * Call is controlled by the specific API methods.
     * Use ErrorHandler::handle instead of this
     *
     * @throws DomainException
     *
     * @deprecated
     */
    protected function handleErrors(ResponseInterface $response): void
    {
        (new ErrorHandler())->handleResponse($response);
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $params Request parameters
     *
     * @throws \JsonException
     */
    private function createJsonBody(array $params): ?string
    {
        $options = empty($params) ? \JSON_FORCE_OBJECT : 0;

        return (\count($params) === 0) ? null : JSON::encode($params, $options);
    }
}
