<?php

declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Http\Client\HttpClient;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Shoman4eg\Nalog\Model\User\UserType;
use Shoman4eg\Nalog\RequestBuilder;
use Webmozart\Assert\Assert;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
class Receipt extends BaseHttpApi
{
    private UserType $profile;

    public function __construct(HttpClient $httpClient, RequestBuilder $requestBuilder, UserType $profile)
    {
        parent::__construct($httpClient, $requestBuilder);
        $this->profile = $profile;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function print(string $receiptUuid): ResponseInterface
    {
        Assert::notEmpty($receiptUuid);

        return $this->httpGet(sprintf('/receipt/%s/%s/print', $this->profile->getInn(), $receiptUuid));
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function json(string $receiptUuid): ResponseInterface
    {
        Assert::notEmpty($receiptUuid);

        return $this->httpGet(sprintf('/receipt/%s/%s/json', $this->profile->getInn(), $receiptUuid));
    }
}
