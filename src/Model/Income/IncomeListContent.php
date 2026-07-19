<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\AbstractCollection;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-import-type IncomeListContentItemData from IncomeListContentItem
 *
 * @extends AbstractCollection<IncomeListContentItem>
 */
final class IncomeListContent extends AbstractCollection implements CreatableFromArray
{
    private function __construct() {}

    /**
     * @param list<IncomeListContentItemData> $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $items = array_map(IncomeListContentItem::createFromArray(...), $data);

        $model = new self();
        $model->setItems($items);

        return $model;
    }
}
