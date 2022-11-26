<?php

namespace Shoman4eg\Nalog\Tests\Api;

use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 * @coversNothing
 */
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
        self::markTestIncomplete();
    }

    public function testPrint(): void
    {
        self::markTestIncomplete();
    }
}
