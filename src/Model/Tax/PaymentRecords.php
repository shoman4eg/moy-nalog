<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Tax;

use Shoman4eg\Nalog\Model\AbstractCollection;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 * @method Payment current()
 */
final class PaymentRecords extends AbstractCollection implements CreatableFromArray
{
    private function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $items = array_map(static fn ($record) => Payment::createFromArray($record), $data['records']);

        $model = new self();
        $model->setItems($items);

        return $model;
    }
}
