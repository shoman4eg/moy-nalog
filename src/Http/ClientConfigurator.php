<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Http;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Shoman4eg\Nalog\DTO\DeviceInfo;

/**
 * Configure an HTTP client.
 *
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class ClientConfigurator
{
    private string $endpoint = 'https://lknpd.nalog.ru/api';
    private string $version = 'v1';
    private UriFactoryInterface $uriFactory;

    /**
     * This is the client we use for actually sending the requests.
     */
    private ClientInterface $httpClient;

    /**
     * This is the client wrapping the $httpClient.
     */
    private PluginClient $configuredClient;

    /**
     * @var Plugin[]
     */
    private array $prependPlugins = [];

    /**
     * @var Plugin[]
     */
    private array $appendPlugins = [];

    /**
     * True if we should create a new Plugin client at next request.
     */
    private bool $configurationModified = true;

    public function __construct(ClientInterface $httpClient = null, UriFactoryInterface $uriFactory = null)
    {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->uriFactory = $uriFactory ?? Psr17FactoryDiscovery::findUriFactory();
    }

    public function createConfiguredClient(): PluginClient
    {
        if ($this->configurationModified) {
            $this->configurationModified = false;
            $plugins = $this->prependPlugins;
            $plugins[] = new Plugin\BaseUriPlugin($this->getEndpoint());
            $plugins[] = new Plugin\HeaderDefaultsPlugin([
                'User-Agent' => DeviceInfo::USER_AGENT,
                'Content-type' => 'application/json',
                'Accept' => 'application/json, text/plain, */*',
                'Accept-language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            ]);

            $this->configuredClient = new PluginClient(
                $this->httpClient,
                \array_merge($plugins, $this->appendPlugins)
            );
        }

        return $this->configuredClient;
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function appendPlugin(Plugin ...$plugin): void
    {
        $this->configurationModified = true;
        foreach ($plugin as $p) {
            $this->appendPlugins[] = $p;
        }
    }

    public function prependPlugin(Plugin ...$plugin): void
    {
        $this->configurationModified = true;
        $plugin = \array_reverse($plugin);
        foreach ($plugin as $p) {
            \array_unshift($this->prependPlugins, $p);
        }
    }

    /**
     * Remove a plugin by its fully qualified class name (FQCN).
     */
    public function removePlugin(string $fqcn): void
    {
        foreach ($this->prependPlugins as $idx => $plugin) {
            if ($plugin instanceof $fqcn) {
                unset($this->prependPlugins[$idx]);
                $this->configurationModified = true;
            }
        }

        foreach ($this->appendPlugins as $idx => $plugin) {
            if ($plugin instanceof $fqcn) {
                unset($this->appendPlugins[$idx]);
                $this->configurationModified = true;
            }
        }
    }

    public function getEndpoint(): UriInterface
    {
        return $this->uriFactory->createUri(sprintf('%s/%s', $this->endpoint, $this->version));
    }
}
