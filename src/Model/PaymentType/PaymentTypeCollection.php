<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\PaymentType;

use Shoman4eg\Nalog\Model\AbstractCollection;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-import-type PaymentTypeData from PaymentType
 *
 * @extends AbstractCollection<PaymentType>
 */
final class PaymentTypeCollection extends AbstractCollection implements CreatableFromArray
{
    private function __construct() {}

    /**
     * @param array{items: list<PaymentTypeData>} $data
     */
    public static function createFromArray(array $data): self
    {
        $items = array_map(PaymentType::createFromArray(...), $data['items']);

        $model = new self();
        $model->setItems($items);

        return $model;
    }
}
