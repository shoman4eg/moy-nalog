<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Tax;

use Shoman4eg\Nalog\Model\AbstractCollection;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class HistoryRecords extends AbstractCollection implements CreatableFromArray
{
    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $items = array_map(static fn ($record) => History::createFromArray($record), $data['recors']);

        $model = new self();
        $model->setItems($items);

        return $model;
    }
}
