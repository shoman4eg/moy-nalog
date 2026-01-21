<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\AbstractCollection;
use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @extends AbstractCollection<IncomeListContent>
 */
final class IncomeListContent extends AbstractCollection implements CreatableFromArray
{
    private function __construct() {}

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $items = array_map(static fn (array $item) => IncomeListContentItem::createFromArray($item), $data);

        $model = new self();
        $model->setItems($items);

        return $model;
    }
}
