<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Exception\Domain;

use Shoman4eg\Nalog\Exception\DomainException;

/**
 * @author Nikita Komissarov <me@nikita-komissarov.ru>
 */
final class PhoneException extends \Exception implements DomainException {}
