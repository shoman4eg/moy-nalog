<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Brick\Math\BigDecimal;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\DTO;
use Shoman4eg\Nalog\Enum;
use Shoman4eg\Nalog\ErrorHandler;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\Model\Income\CancelIncomeInfoType;
use Shoman4eg\Nalog\Model\Income\IncomeList;
use Shoman4eg\Nalog\Model\Income\IncomeType;
use Webmozart\Assert\Assert;

final class Income extends BaseHttpApi
{
    public const SORT_OPERATION_TIME_DESC = 'operation_time:desc';
    public const SORT_OPERATION_TIME_ASC = 'operation_time:asc';
    public const SORT_TOTAL_AMOUNT_DESC = 'total_amount:desc';
    public const SORT_TOTAL_AMOUNT_ASC = 'total_amount:asc';
    private const MIN_LIST_LIMIT = 1;
    private const MAX_LIST_LIMIT = 100;
    private const ALLOWED_SORT_BY = [
        self::SORT_OPERATION_TIME_DESC,
        self::SORT_OPERATION_TIME_ASC,
        self::SORT_TOTAL_AMOUNT_DESC,
        self::SORT_TOTAL_AMOUNT_ASC,
    ];

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function create(
        string $name,
        float|int|string $amount,
        float|int $quantity,
        ?\DateTimeInterface $operationTime = null,
        ?DTO\IncomeClient $client = null,
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
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function createMultipleItems(
        array $serviceItems,
        ?\DateTimeInterface $operationTime = null,
        ?DTO\IncomeClient $client = null,
    ): IncomeType {
        Assert::minCount($serviceItems, 1, 'Items cannot be empty');
        Assert::allIsInstanceOf($serviceItems, DTO\IncomeServiceItem::class);

        foreach ($serviceItems as $key => $serviceItem) {
            Assert::notEmpty($serviceItem->name, "Name of item[{$key}] cannot be empty");
            Assert::numeric($serviceItem->amount, "Amount of item[{$key}] must be int or float");
            Assert::greaterThan($serviceItem->amount, 0, "Amount of item[{$key}] must be greater than %2\$s");
            Assert::notEmpty($serviceItem->quantity, "Quantity of item[{$key}] cannot be empty");
            Assert::numeric($serviceItem->quantity, "Quantity of item[{$key}] must be int or float");
            Assert::greaterThan($serviceItem->quantity, 0, "Quantity of item[{$key}] must be greater than %2\$s");
        }

        $totalAmount = array_reduce(
            $serviceItems,
            fn ($totalAmount, $serviceItem): BigDecimal => $totalAmount->plus($serviceItem->getTotalAmount()),
            BigDecimal::of(0)
        );

        if ($client !== null && $client->incomeType === Enum\IncomeType::LEGAL_ENTITY) {
            Assert::notEmpty($client->inn, 'Client INN cannot be empty');
            Assert::numeric($client->inn, 'Client INN must contain only numbers');
            Assert::oneOf(mb_strlen($client->inn), [10, 12], 'Client INN length must been 10 or 12');
            Assert::notEmpty($client->displayName, 'Client DisplayName cannot be empty');
        }

        $response = $this->httpPost('/income', [
            'operationTime' => new DTO\DateTime($operationTime ?? new \DateTimeImmutable()),
            'requestTime' => new DTO\DateTime(new \DateTimeImmutable()),
            'services' => $serviceItems,
            'totalAmount' => (string)$totalAmount,
            'client' => $client ?? new DTO\IncomeClient(),
            'paymentType' => Enum\PaymentType::CASH->value,
            'ignoreMaxTotalIncomeRestriction' => false,
        ]);

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $this->hydrator->hydrate($response, IncomeType::class);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function list(
        ?\DateTimeInterface $from = null,
        ?\DateTimeInterface $to = null,
        ?int $offset = 0,
        ?int $limit = 100,
        ?string $sortBy = self::SORT_OPERATION_TIME_DESC,
        ?Enum\BuyerType $buyerType = null,
        ?Enum\ReceiptType $receiptType = null,
    ): IncomeList {
        $query = [];

        if ($from !== null) {
            $query['from'] = $from->format(\DateTimeInterface::RFC3339_EXTENDED);
        }

        if ($to !== null) {
            $query['to'] = $to->format(\DateTimeInterface::RFC3339_EXTENDED);
        }

        $query['limit'] = max(self::MIN_LIST_LIMIT, min($limit, self::MAX_LIST_LIMIT));
        $query['offset'] = $offset;

        if ($sortBy !== null) {
            Assert::oneOf($sortBy, self::ALLOWED_SORT_BY, 'Sort is invalid. Must be one of: %2$s');
            $query['sortBy'] = $sortBy;
        }

        if ($buyerType !== null) {
            $query['buyerType'] = $buyerType->value;
        }

        if ($receiptType !== null) {
            $query['receiptType'] = $receiptType->value;
        }

        $response = $this->httpGet('/incomes', $query);

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $this->hydrator->hydrate($response, IncomeList::class);
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function cancel(
        string $receiptUuid,
        Enum\CancelCommentType|string $comment,
        ?\DateTimeImmutable $operationTime = null,
        ?\DateTimeImmutable $requestTime = null,
        ?string $partnerCode = null,
    ): CancelIncomeInfoType {
        Assert::notEmpty($receiptUuid, 'ReceiptUuid cannot be empty');

        $commentValue = $comment instanceof Enum\CancelCommentType ? $comment->value : $comment;
        Assert::oneOf(
            $commentValue,
            array_column(Enum\CancelCommentType::cases(), 'value'),
            'Comment is invalid. Must be one of: %2$s'
        );

        $response = $this->httpPost('/cancel', [
            'operationTime' => new DTO\DateTime($operationTime ?? new \DateTimeImmutable()),
            'requestTime' => new DTO\DateTime($requestTime ?? new \DateTimeImmutable()),
            'comment' => $commentValue,
            'receiptUuid' => $receiptUuid,
            'partnerCode' => $partnerCode,
        ]);

        if ($response->getStatusCode() >= 400) {
            (new ErrorHandler())->handleResponse($response);
        }

        return $this->hydrator->hydrate($response, CancelIncomeInfoType::class);
    }
}
