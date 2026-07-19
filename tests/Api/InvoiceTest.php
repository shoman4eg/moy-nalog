<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Shoman4eg\Nalog\Exception\DomainException;
use Shoman4eg\Nalog\Tests\ApiTestCase;
use Testo\Assert;
use Testo\Codecov\CoversNothing;
use Testo\Data\DataProvider;
use Testo\Expect;
use Testo\Test;

/**
 * @internal
 */
#[Test]
#[CoversNothing]
final class InvoiceTest extends ApiTestCase
{
    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    public function testCreate(): void
    {
        $receiptId = 'randomReceiptId';
        $this->appendSuccessJson(['approvedReceiptUuid' => $receiptId]);

        $response = $this->client->invoice()->create('service', 100, 2);
        Assert::same($response->approvedReceiptUuid, $receiptId);
    }

    /**
     * @throws \JsonException
     * @throws ClientExceptionInterface
     * @throws DomainException
     */
    #[DataProvider('validationCreateDataProvider')]
    public function testValidationCreate(
        string $name,
        float|int|string $amount,
        float|int $quantity,
        string $message,
    ): void {
        Expect::exception(\InvalidArgumentException::class)->withMessageContaining($message);
        $this->client->invoice()->create($name, $amount, $quantity);
    }

    public static function validationCreateDataProvider(): iterable
    {
        yield ['', 100, 1, 'Name cannot be empty'];
        yield ['name', '', 1, 'Amount must be int or float'];
        yield ['name', -1, 1, 'Amount must be greater than 0'];
        yield ['name', 1, 0, 'Quantity must be greater than 0'];
    }

    public function testCancelThrows(): never
    {
        Expect::exception(\BadMethodCallException::class);
        $this->client->invoice()->cancel(1);
    }

    public function testUpdatePaymentInfoThrows(): never
    {
        Expect::exception(\BadMethodCallException::class);
        $this->client->invoice()->updatePaymentInfo();
    }
}
