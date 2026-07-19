<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversNothing;
use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @internal
 */
#[CoversNothing]
final class ReceiptTest extends ApiTestCase
{
    public function testPrintUrl(): void
    {
        $receiptId = 'dasdasdasd';
        $response = $this->client->receipt()->printUrl($receiptId);
        $expected = sprintf('/receipt/%s/%s/print', '3000000000000', $receiptId);
        self::assertStringContainsString($expected, $response);
    }

    public function testJson(): void
    {
        $receiptId = 'testReceiptId';
        $body = '{"someKey":"someValue"}';
        $this->mock->append(new Response(200, ['Content-Type' => 'application/json'], $body));

        $result = $this->client->receipt()->json($receiptId);
        self::assertSame($body, $result);
    }

    public function testPrint(): void
    {
        $receiptId = 'testReceiptId';
        $this->mock->append(new Response(200, [], 'pdf-content'));

        $response = $this->client->receipt()->print($receiptId);
        self::assertSame(200, $response->getStatusCode());
    }
}
