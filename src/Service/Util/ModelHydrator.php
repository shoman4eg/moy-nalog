<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Service\Util;

use Psr\Http\Message\ResponseInterface;
use Shoman4eg\Nalog\Exception\HydrationException;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class ModelHydrator
{
    /**
     * @template T of CreatableFromArray
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    public function hydrate(ResponseInterface $response, string $class)
    {
        $body = $response->getBody()->__toString();
        if (\mb_strpos($response->getHeaderLine('Content-Type'), 'application/json') !== 0) {
            throw new HydrationException(sprintf('The ModelHydrator cannot hydrate response with Content-Type: %s', $response->getHeaderLine('Content-Type')));
        }

        try {
            $data = JSON::decode($body);
        } catch (\JsonException $e) {
            throw new HydrationException(\sprintf('Error (%d) when trying to json_decode response', $e->getCode()));
        }

        if (!\is_array($data)) {
            throw new HydrationException('The ModelHydrator cannot hydrate a non-array JSON response');
        }

        return $class::createFromArray($data);
    }
}
