<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Brick\Math\BigDecimal;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\DTO;
use Shoman4eg\Nalog\Enum;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception;
use Shoman4eg\Nalog\Model\Income\IncomeType;
use Webmozart\Assert\Assert;

final class Invoice extends BaseHttpApi
{
    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     */
    public function create(
        string $name,
        float|int|string $amount,
        float|int $quantity,
        ?\DateTimeInterface $operationTime = null,
    ): IncomeType {
        Assert::notEmpty($name, 'Name cannot be empty');
        Assert::numeric($amount, 'Amount must be int or float');
        Assert::greaterThan($amount, 0, 'Amount must be greater than %2$s');
        Assert::notEmpty($quantity, 'Quantity cannot be empty');
        Assert::numeric($quantity, 'Quantity must be int or float');
        Assert::greaterThan($quantity, 0, 'Quantity must be greater than %2$s');

        $totalAmount = BigDecimal::of($amount)->multipliedBy($quantity);

        $response = $this->httpPost('/invoice', [
            'paymentType' => Enum\PaymentType::ACCOUNT->value,
            'ignoreMaxTotalIncomeRestriction' => false,
            'client' => new DTO\IncomeClient(),
            'services' => [new DTO\InvoiceServiceItem($name, $amount, $quantity)],
            'requestTime' => new DTO\DateTime(new \DateTimeImmutable()),
            'operationTime' => new DTO\DateTime($operationTime ?? new \DateTimeImmutable()),
            'totalAmount' => (string)$totalAmount,
        ]);

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $this->hydrator->hydrate($response, IncomeType::class);
    }

    public function cancel(int $invoiceId): never
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function updatePaymentInfo(): never
    {
        throw new \BadMethodCallException('Not implemented');
    }
}
