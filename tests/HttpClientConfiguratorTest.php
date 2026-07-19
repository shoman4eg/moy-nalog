<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shoman4eg\Nalog\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Http\Client\Common\Plugin\HeaderAppendPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Nyholm\NSA;
use Psr\Log\AbstractLogger;
use Psr\Log\NullLogger;
use Shoman4eg\Nalog\ApiClient;
use Shoman4eg\Nalog\Http\ClientConfigurator;
use Testo\Assert;
use Testo\Test;

#[Test]
final class HttpClientConfiguratorTest
{
    public function testAppendPlugin(): void
    {
        $hcc = new ClientConfigurator();
        $plugin0 = new HeaderAppendPlugin(['plugin0']);

        $hcc->appendPlugin($plugin0);
        $plugins = NSA::getProperty($hcc, 'appendPlugins');
        Assert::count($plugins, 1);
        Assert::equals($plugins[0], $plugin0);

        $plugin1 = new HeaderAppendPlugin(['plugin1']);
        $hcc->appendPlugin($plugin1);
        $plugins = NSA::getProperty($hcc, 'appendPlugins');
        Assert::count($plugins, 2);
        Assert::equals($plugins[1], $plugin1);
    }

    public function testAppendPluginMultiple(): void
    {
        $hcc = new ClientConfigurator();
        $plugin0 = new HeaderAppendPlugin(['plugin0']);
        $plugin1 = new HeaderAppendPlugin(['plugin1']);

        $hcc->appendPlugin($plugin0, $plugin1);
        $plugins = NSA::getProperty($hcc, 'appendPlugins');
        Assert::count($plugins, 2);
        Assert::equals($plugins[0], $plugin0);
        Assert::equals($plugins[1], $plugin1);
    }

    public function testPrependPlugin(): void
    {
        $hcc = new ClientConfigurator();
        $plugin0 = new HeaderAppendPlugin(['plugin0']);

        $hcc->prependPlugin($plugin0);
        $plugins = NSA::getProperty($hcc, 'prependPlugins');
        Assert::count($plugins, 1);
        Assert::equals($plugins[0], $plugin0);

        $plugin1 = new HeaderAppendPlugin(['plugin1']);
        $hcc->prependPlugin($plugin1);
        $plugins = NSA::getProperty($hcc, 'prependPlugins');
        Assert::count($plugins, 2);
        Assert::equals($plugins[0], $plugin1);
    }

    public function testPrependPluginMultiple(): void
    {
        $hcc = new ClientConfigurator();
        $plugin0 = new HeaderAppendPlugin(['plugin0']);
        $plugin1 = new HeaderAppendPlugin(['plugin1']);

        $hcc->prependPlugin($plugin0, $plugin1);
        $plugins = NSA::getProperty($hcc, 'prependPlugins');
        Assert::count($plugins, 2);
        Assert::equals($plugins[0], $plugin0);
        Assert::equals($plugins[1], $plugin1);
    }

    public function testSetLoggerAppendsLoggerPlugin(): void
    {
        $hcc = new ClientConfigurator();
        $hcc->setLogger(new NullLogger());

        $plugins = NSA::getProperty($hcc, 'appendPlugins');
        Assert::count($plugins, 1);
        Assert::instanceOf($plugins[0], LoggerPlugin::class);
    }

    public function testSetLoggerIsIdempotent(): void
    {
        $hcc = new ClientConfigurator();
        $hcc->setLogger(new NullLogger());
        $hcc->setLogger(new NullLogger());

        // Calling setLogger twice replaces the plugin instead of stacking a second one.
        Assert::count(NSA::getProperty($hcc, 'appendPlugins'), 1);
    }

    public function testLoggerRecordsRequestAndResponse(): void
    {
        $logger = new class extends AbstractLogger {
            /** @var list<string> */
            public array $messages = [];

            public function log($level, string|\Stringable $message, array $context = []): void
            {
                $this->messages[] = (string)$message;
            }
        };

        $userJson = json_encode([
            'lastName' => null,
            'id' => 1000000,
            'displayName' => 'Surname Name',
            'middleName' => null,
            'email' => null,
            'phone' => '79000000000',
            'inn' => '300000000000',
            'snils' => null,
            'avatarExists' => false,
            'initialRegistrationDate' => null,
            'registrationDate' => null,
            'firstReceiptRegisterTime' => null,
            'firstReceiptCancelTime' => null,
            'hideCancelledReceipt' => false,
            'registerAvailable' => null,
            'status' => 'ACTIVE',
            'restrictedMode' => false,
            'pfrUrl' => null,
            'login' => null,
        ], JSON_THROW_ON_ERROR);

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], $userJson),
        ]);
        $configurator = new ClientConfigurator(new Client(['handler' => new HandlerStack($mock)]));
        $configurator->setLogger($logger);

        $client = new ApiClient(clientConfigurator: $configurator);
        $client->authenticate(ApiTestCase::getAccessToken());
        $client->user()->get();

        Assert::false(empty($logger->messages));
        Assert::string(implode("\n", $logger->messages))->contains('Sending request');
        Assert::string(implode("\n", $logger->messages))->contains('Received response');
    }
}
