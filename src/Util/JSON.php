<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Util;

/**
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
final class JSON
{
    /**
     * Generic JSON encode method with error handling support.
     *
     * @see http://php.net/manual/en/function.json-encode.php
     * @see http://php.net/manual/en/function.json-last-error.php
     *
     * @param mixed    $input   The value being encoded. Can be any type except a resource.
     * @param null|int $options Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS,
     *                          JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT,
     *                          JSON_PRESERVE_ZERO_FRACTION, JSON_UNESCAPED_UNICODE, JSON_PARTIAL_OUTPUT_ON_ERROR.
     *                          The behaviour of these constants is described on the JSON constants page.
     * @param null|int $depth   Set the maximum depth. Must be greater than zero.
     *
     * @psalm-param null|int<1, 2147483647> $depth
     *
     * @throws \JsonException
     */
    public static function encode($input, ?int $options = null, ?int $depth = null): string
    {
        $options ??= 0;
        $depth ??= 512;

        return \json_encode($input, JSON_THROW_ON_ERROR | $options, $depth);
    }

    /**
     * Generic JSON decode method with error handling support.
     *
     * @see http://php.net/manual/en/function.json-decode.php
     * @see http://php.net/manual/en/function.json-last-error.php
     *
     * @param string    $json    the json string being decoded
     * @param null|bool $assoc   when TRUE, returned objects will be converted into associative arrays
     * @param null|int  $depth   user specified recursion depth
     * @param null|int  $options Bitmask of JSON decode options. Currently only JSON_BIGINT_AS_STRING is supported
     *                           (default is to cast large integers as floats)
     *
     * @psalm-param null|int<1, 2147483647> $depth
     *
     * @return mixed
     *
     * @throws \JsonException
     */
    public static function decode(string $json, ?bool $assoc = null, ?int $depth = null, ?int $options = null)
    {
        $assoc ??= true;
        $depth ??= 512;
        $options ??= 0;

        return \json_decode($json, $assoc, $depth, JSON_THROW_ON_ERROR | $options);
    }
}
