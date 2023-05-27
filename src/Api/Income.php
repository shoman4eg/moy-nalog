<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Brick\Math\BigDecimal;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\DTO;
use Shoman4eg\Nalog\Enum;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception;
use Shoman4eg\Nalog\Model\Income\IncomeInfoType;
use Shoman4eg\Nalog\Model\Income\IncomeType;
use Webmozart\Assert\Assert;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class Income extends BaseHttpApi
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
        return $this->createMultipleItems(
            [new DTO\IncomeServiceItem($name, $amount, $quantity)],
            $operationTime,
            $client
        );
    }

    /**
     * @param DTO\IncomeServiceItem[] $serviceItems
     *
     * @throws ClientExceptionInterface
     * @throws Exception\DomainException
     * @throws \JsonException
     */
    public function createMultipleItems(
        array $serviceItems,
        \DateTimeInterface $operationTime = null,
        DTO\IncomeClient $client = null
    ): IncomeType {
        Assert::minCount($serviceItems, 1, 'Items cannot be empty');
        Assert::allIsInstanceOf($serviceItems, DTO\IncomeServiceItem::class);

        foreach ($serviceItems as $key => $serviceItem) {
            Assert::notEmpty($serviceItem->getName(), "Name of item[{$key}] cannot be empty");
            Assert::numeric($serviceItem->getAmount(), "Amount of item[{$key}] must be int or float");
            Assert::greaterThan($serviceItem->getAmount(), 0, "Amount of item[{$key}] must be greater than %2\$s");
            Assert::notEmpty($serviceItem->getQuantity(), "Quantity of item[{$key}] cannot be empty");
            Assert::numeric($serviceItem->getQuantity(), "Quantity of item[{$key}] must be int or float");
            Assert::greaterThan($serviceItem->getQuantity(), 0, "Quantity of item[{$key}] must be greater than %2\$s");
        }

        $totalAmount = array_reduce(
            $serviceItems,
            fn ($totalAmount, $serviceItem): BigDecimal => $totalAmount->plus($serviceItem->getTotalAmount()),
            BigDecimal::of(0)
        );

        if ($client !== null && $client->getIncomeType() === Enum\IncomeType::LEGAL_ENTITY) {
            Assert::notEmpty($client->getInn(), 'Client INN cannot be empty');
            Assert::numeric($client->getInn(), 'Client INN must contain only numbers');
            Assert::oneOf(mb_strlen($client->getInn()), [10, 12], 'Client INN length must been 10 or 12');
            Assert::notEmpty($client->getDisplayName(), 'Client DisplayName cannot be empty');
        }

        $response = $this->httpPost('/income', [
            'operationTime' => new DTO\DateTime($operationTime ?: new \DateTimeImmutable()),
            'requestTime' => new DTO\DateTime(new \DateTimeImmutable()),
            'services' => $serviceItems,
            'totalAmount' => (string)$totalAmount,
            'client' => $client ?? new DTO\IncomeClient(),
            'paymentType' => Enum\PaymentType::CASH,
            'ignoreMaxTotalIncomeRestriction' => false,
        ]);

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
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
        Assert::oneOf($comment, Enum\CancelCommentType::all(), 'Comment is invalid. Must be one of: %2$s');

        $response = $this->httpPost('/cancel', [
            'operationTime' => new DTO\DateTime($operationTime ?: new \DateTimeImmutable()),
            'requestTime' => new DTO\DateTime($requestTime ?: new \DateTimeImmutable()),
            'comment' => $comment,
            'receiptUuid' => $receiptUuid,
            'partnerCode' => $partnerCode,
        ]);

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $this->hydrator->hydrate($response, IncomeInfoType::class);
    }
}
