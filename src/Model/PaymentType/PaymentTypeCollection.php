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
    public static function createFromArray(array $data): self
    {
        $data = $data['items'];
        $items = [];

        foreach ($data as $item) {
            $items[] = PaymentType::createFromArray($item);
        }

        $model = new self();
        $model->setItems($items);

        return $model;
    }
}
