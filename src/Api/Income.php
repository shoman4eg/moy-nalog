<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Api;

use Brick\Math\BigDecimal;
use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\Model\Income\IncomeType;
use Shoman4eg\Nalog\Model\Request\Client;
use Shoman4eg\Nalog\Model\Request\DateTime;
use Shoman4eg\Nalog\Model\Request\ServiceItem;
use Webmozart\Assert\Assert;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
class Income extends BaseHttpApi
{
    private const PAYMENT_TYPE_CASH = 'CASH';

    /**
     * @param string                  $name          Наименование товара/услуги
     * @param float|int               $amount        Стоимость
     * @param float|int               $quantity      Количество
     * @param null|\DateTimeInterface $operationTime время поступления денег
     *
     * @throws ClientExceptionInterface
     */
    public function create(string $name, $amount, $quantity, \DateTimeInterface $operationTime = null): IncomeType
    {
        Assert::notEmpty($name, 'Name cannot be empty');
        Assert::numeric($amount, 'Amount must be int or float');
        Assert::greaterThanEq($amount, 0, 'Amount cannot be empty');
        Assert::notEmpty($quantity, 'Quantity cannot be empty');
        Assert::numeric($quantity, 'Quantity must be int or float');
        Assert::greaterThan($quantity, 0, 'Quantity must be greater than %2$s');

        $totalAmount = BigDecimal::of($amount)->multipliedBy($quantity);

        $response = $this->httpPost('/income', [
            'paymentType' => self::PAYMENT_TYPE_CASH,
            'ignoreMaxTotalIncomeRestriction' => false,
            'client' => new Client(),
            'services' => [new ServiceItem($name, $amount, $quantity)],
            'requestTime' => new DateTime(new \DateTimeImmutable()),
            'operationTime' => new DateTime($operationTime ?: new \DateTimeImmutable()),
            'totalAmount' => (string)$totalAmount,
        ]);

        return $this->hydrator->hydrate($response, IncomeType::class);
    }
}
