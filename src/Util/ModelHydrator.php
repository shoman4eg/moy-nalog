<?php

namespace Shoman4eg\Nalog\Util;

use Psr\Http\Message\ResponseInterface;
use Shoman4eg\Nalog\Exception\HydrationException;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class ModelHydrator
{
    /**
     * @template T
     *
     * @param class-string<CreatableFromArray>|class-string<T> $class
     *
     * @return mixed|T
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

        if (\is_subclass_of($class, CreatableFromArray::class)) {
            $object = \call_user_func($class.'::createFromArray', $data);
        } else {
            $object = new $class($data);
        }

        return $object;
    }
}
