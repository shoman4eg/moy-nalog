<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Tax;

use Shoman4eg\Nalog\Model\AbstractCollection;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-import-type PaymentData from Payment
 *
 * @extends AbstractCollection<Payment>
 */
final class PaymentRecords extends AbstractCollection implements CreatableFromArray
{
    private function __construct() {}

    /**
     * @param array{records: list<PaymentData>} $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $items = array_map(Payment::createFromArray(...), $data['records']);

        $model = new self();
        $model->setItems($items);

        return $model;
    }
}
