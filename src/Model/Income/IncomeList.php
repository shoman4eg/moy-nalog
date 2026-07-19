<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-import-type IncomeListContentItemData from IncomeListContentItem
 *
 * @phpstan-type IncomeListData array{
 *     currentLimit: int,
 *     currentOffset: int,
 *     hasMore: bool,
 *     content: list<IncomeListContentItemData>,
 * }
 */
final readonly class IncomeList implements CreatableFromArray
{
    public bool $hasMore;
    public int $currentOffset;
    public int $currentLimit;
    public IncomeListContent $content;

    /**
     * @param IncomeListData $data
     *
     * @throws \Exception
     */
    private function __construct(array $data)
    {
        $this->currentLimit = $data['currentLimit'];
        $this->currentOffset = $data['currentOffset'];
        $this->hasMore = $data['hasMore'];
        $this->content = IncomeListContent::createFromArray($data['content']);
    }

    /**
     * @param IncomeListData $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
