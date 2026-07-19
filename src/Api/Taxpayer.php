<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\Model\Taxpayer\Bonus;
use Shoman4eg\Nalog\Model\Taxpayer\Debts;

final class Taxpayer extends BaseHttpApi
{
    /**
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function debts(): Debts
    {
        $response = $this->httpGet('/taxpayer/debts');

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $this->hydrator->hydrate($response, Debts::class);
    }

    /**
     * @throws \Exception
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function bonus(): Bonus
    {
        $response = $this->httpGet('/taxpayer/bonus');

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $this->hydrator->hydrate($response, Bonus::class);
    }
}
