<?php

namespace Shoman4eg\Nalog\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\Model\User\UserType;

class User extends BaseHttpApi
{
    /**
     * @throws ClientExceptionInterface
     */
    public function get(): UserType
    {
        $response = $this->httpGet('/user');

        return $this->hydrator->hydrate($response, UserType::class);
    }
}
