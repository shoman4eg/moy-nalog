<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Exception\Domain;

use Shoman4eg\Nalog\Exception\DomainException;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class UnknownErrorException extends \Exception implements DomainException
{
}
