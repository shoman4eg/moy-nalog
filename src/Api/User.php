<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\Model\User\UserType;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class User extends BaseHttpApi
{
    /**
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function get(): UserType
    {
        $response = $this->httpGet('/user');

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $this->hydrator->hydrate($response, UserType::class);
    }
}
