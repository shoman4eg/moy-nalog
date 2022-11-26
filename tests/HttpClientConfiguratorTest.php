<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shoman4eg\Nalog\Tests;

use Http\Client\Common\Plugin\HeaderAppendPlugin;
use Nyholm\NSA;
use PHPUnit\Framework\TestCase;
use Shoman4eg\Nalog\Http\ClientConfigurator;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 * @coversNothing
 */
final class HttpClientConfiguratorTest extends TestCase
{
    public function testAppendPlugin(): void
    {
        $hcc = new ClientConfigurator();
        $plugin0 = new HeaderAppendPlugin(['plugin0']);

        $hcc->appendPlugin($plugin0);
        $plugins = NSA::getProperty($hcc, 'appendPlugins');
        $this->assertCount(1, $plugins);
        $this->assertEquals($plugin0, $plugins[0]);

        $plugin1 = new HeaderAppendPlugin(['plugin1']);
        $hcc->appendPlugin($plugin1);
        $plugins = NSA::getProperty($hcc, 'appendPlugins');
        $this->assertCount(2, $plugins);
        $this->assertEquals($plugin1, $plugins[1]);
    }

    public function testAppendPluginMultiple(): void
    {
        $hcc = new ClientConfigurator();
        $plugin0 = new HeaderAppendPlugin(['plugin0']);
        $plugin1 = new HeaderAppendPlugin(['plugin1']);

        $hcc->appendPlugin($plugin0, $plugin1);
        $plugins = NSA::getProperty($hcc, 'appendPlugins');
        $this->assertCount(2, $plugins);
        $this->assertEquals($plugin0, $plugins[0]);
        $this->assertEquals($plugin1, $plugins[1]);
    }

    public function testPrependPlugin(): void
    {
        $hcc = new ClientConfigurator();
        $plugin0 = new HeaderAppendPlugin(['plugin0']);

        $hcc->prependPlugin($plugin0);
        $plugins = NSA::getProperty($hcc, 'prependPlugins');
        $this->assertCount(1, $plugins);
        $this->assertEquals($plugin0, $plugins[0]);

        $plugin1 = new HeaderAppendPlugin(['plugin1']);
        $hcc->prependPlugin($plugin1);
        $plugins = NSA::getProperty($hcc, 'prependPlugins');
        $this->assertCount(2, $plugins);
        $this->assertEquals($plugin1, $plugins[0]);
    }

    public function testPrependPluginMultiple(): void
    {
        $hcc = new ClientConfigurator();
        $plugin0 = new HeaderAppendPlugin(['plugin0']);
        $plugin1 = new HeaderAppendPlugin(['plugin1']);

        $hcc->prependPlugin($plugin0, $plugin1);
        $plugins = NSA::getProperty($hcc, 'prependPlugins');
        $this->assertCount(2, $plugins);
        $this->assertEquals($plugin0, $plugins[0]);
        $this->assertEquals($plugin1, $plugins[1]);
    }
}
