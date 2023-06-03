<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\Model\Tax\HistoryRecords;
use Shoman4eg\Nalog\Model\Tax\PaymentRecords;
use Shoman4eg\Nalog\Model\Tax\Tax as TaxModel;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class Tax extends BaseHttpApi
{
    /**
     * @throws ClientExceptionInterface
     */
    public function get(): TaxModel
    {
        $response = $this->httpGet('/taxes');

        return $this->hydrator->hydrate($response, TaxModel::class);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function history(string $oktmo = null): HistoryRecords
    {
        $response = $this->httpPost('/taxes/history', [
            'oktmo' => $oktmo,
        ]);

        return $this->hydrator->hydrate($response, HistoryRecords::class);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function payments(string $oktmo = null, bool $onlyPaid = false): PaymentRecords
    {
        $response = $this->httpPost('/taxes/payments', [
            'oktmo' => $oktmo,
            'onlyPaid' => $onlyPaid,
        ]);

        return $this->hydrator->hydrate($response, PaymentRecords::class);
    }
}
