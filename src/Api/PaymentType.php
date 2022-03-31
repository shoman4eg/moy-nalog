<?php

namespace Shoman4eg\Nalog\Api;

use Shoman4eg\Nalog\Model\PaymentType\PaymentTypeCollection;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
class PaymentType extends BaseHttpApi
{
    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function table(): PaymentTypeCollection
    {
        $response = $this->httpGet('/payment-type/table');

        return $this->hydrator->hydrate($response, PaymentTypeCollection::class);
    }
}
