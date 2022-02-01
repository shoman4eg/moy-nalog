<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Exception\Domain;

use Shoman4eg\Nalog\Exception\DomainException;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ServerException extends \Exception implements DomainException
{
}
