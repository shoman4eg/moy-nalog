<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Http\Client\HttpClient;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception;
use Shoman4eg\Nalog\Model\User\UserType;
use Shoman4eg\Nalog\RequestBuilder;
use Webmozart\Assert\Assert;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class Receipt extends BaseHttpApi
{
    private UserType $profile;
    private string $endpoint;

    public function __construct(
        HttpClient $httpClient,
        RequestBuilder $requestBuilder,
        UserType $profile,
        string $endpoint
    ) {
        parent::__construct($httpClient, $requestBuilder);
        $this->profile = $profile;
        $this->endpoint = $endpoint;
    }

    public function printUrl(string $receiptUuid): string
    {
        return sprintf('%s%s', $this->endpoint, $this->composePrintUrl($receiptUuid));
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     *
     * @deprecated
     */
    public function print(string $receiptUuid): ResponseInterface
    {
        Assert::notEmpty($receiptUuid);

        $response = $this->httpGet($this->composePrintUrl($receiptUuid));

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $response;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     */
    public function json(string $receiptUuid): string
    {
        Assert::notEmpty($receiptUuid);

        $response = $this->httpGet(sprintf('/receipt/%s/%s/json', $this->profile->getInn(), $receiptUuid));

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $response->getBody()->__toString();
    }

    private function composePrintUrl(string $receiptUuid): string
    {
        Assert::notEmpty($receiptUuid);

        return sprintf('/receipt/%s/%s/print', $this->profile->getInn(), $receiptUuid);
    }
}
