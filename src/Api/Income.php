<?php

declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Brick\Math\BigDecimal;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\DTO;
use Shoman4eg\Nalog\Enum\CancelCommentType;
use Shoman4eg\Nalog\Enum\IncomeType as IncomeTypeEnum;
use Shoman4eg\Nalog\Enum\PaymentType;
use Shoman4eg\Nalog\Exception;
use Shoman4eg\Nalog\Model\Income\IncomeInfoType;
use Shoman4eg\Nalog\Model\Income\IncomeType;
use Webmozart\Assert\Assert;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
class Income extends BaseHttpApi
{
    /**
     * @param float|int $amount
     * @param float|int $quantity
     *
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     * @throws \JsonException
     */
    public function create(
        string $name,
        $amount,
        $quantity,
        \DateTimeInterface $operationTime = null,
        DTO\IncomeClient $client = null
    ): IncomeType {
        Assert::notEmpty($name, 'Name cannot be empty');
        Assert::numeric($amount, 'Amount must be int or float');
        Assert::greaterThan($amount, 0, 'Amount must be greater than %2$s');
        Assert::notEmpty($quantity, 'Quantity cannot be empty');
        Assert::numeric($quantity, 'Quantity must be int or float');
        Assert::greaterThan($quantity, 0, 'Quantity must be greater than %2$s');

        if ($client !== null && $client->getIncomeType() === IncomeTypeEnum::LEGAL_ENTITY) {
            Assert::notEmpty($client->getInn(), 'Client INN cannot be empty');
            Assert::numeric($client->getInn(), 'Client INN must contain only numbers');
            Assert::lengthBetween($client->getInn(), 10, 12, 'Client INN length must been 10 or 12');
            Assert::notEmpty($client->getDisplayName(), 'Client DisplayName cannot be empty');
        }

        $totalAmount = BigDecimal::of($amount)->multipliedBy($quantity);

        $response = $this->httpPost('/income', [
            'operationTime' => new DTO\DateTime($operationTime ?: new \DateTimeImmutable()),
            'requestTime' => new DTO\DateTime(new \DateTimeImmutable()),
            'services' => [new DTO\IncomeServiceItem($name, $amount, $quantity)],
            'totalAmount' => (string)$totalAmount,
            'client' => $client ?? new DTO\IncomeClient(),
            'paymentType' => PaymentType::CASH,
            'ignoreMaxTotalIncomeRestriction' => false,
        ]);

        if ($response->getStatusCode() >= 400) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, IncomeType::class);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     * @throws \JsonException
     */
    public function cancel(
        string $receiptUuid,
        string $comment,
        \DateTimeImmutable $operationTime = null,
        \DateTimeImmutable $requestTime = null,
        ?string $partnerCode = null
    ): IncomeInfoType {
        Assert::notEmpty($receiptUuid, 'ReceiptUuid cannot be empty');
        Assert::inArray($comment, CancelCommentType::all(), 'Comment is invalid. Must be one of: %2$s');

        $response = $this->httpPost('/cancel', [
            'operationTime' => new DTO\DateTime($operationTime ?: new \DateTimeImmutable()),
            'requestTime' => new DTO\DateTime($requestTime ?: new \DateTimeImmutable()),
            'comment' => $comment,
            'receiptUuid' => $receiptUuid,
            'partnerCode' => $partnerCode,
        ]);

        if ($response->getStatusCode() >= 400) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, IncomeInfoType::class);
    }
}
