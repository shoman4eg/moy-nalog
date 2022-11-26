<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception;
use Shoman4eg\Nalog\Model\PaymentType\PaymentType as PaymentTypeModel;
use Shoman4eg\Nalog\Model\PaymentType\PaymentTypeCollection;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class PaymentType extends BaseHttpApi
{
    /**
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     */
    public function table(): PaymentTypeCollection
    {
        $response = $this->httpGet('/payment-type/table');

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $this->hydrator->hydrate($response, PaymentTypeCollection::class);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     */
    public function favorite(): ?PaymentTypeModel
    {
        $paymentTypes = $this->table();

        foreach ($paymentTypes as $paymentType) {
            /** @var PaymentTypeModel $paymentType */
            if ($paymentType->isFavorite()) {
                return $paymentType;
            }
        }

        return null;
    }
}
