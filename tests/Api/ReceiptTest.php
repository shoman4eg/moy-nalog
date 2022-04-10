<?php

namespace Shoman4eg\Nalog\Tests\Api;

use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @internal
 * @coversNothing
 */
class ReceiptTest extends ApiTestCase
{
    public function testPrintUrl(): void
    {
        $receiptId = 'dasdasdasd';
//        var_dump();
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
