<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\PaymentType;

use Shoman4eg\Nalog\Model\AbstractCollection;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @extends AbstractCollection<PaymentType>
 */
final class PaymentTypeCollection extends AbstractCollection implements CreatableFromArray
{
    private function __construct() {}

    public static function createFromArray(array $data): self
    {
        $items = array_map(static fn (array $item) => PaymentType::createFromArray($item), $data['items']);

        $model = new self();
        $model->setItems($items);

        return $model;
    }
}
