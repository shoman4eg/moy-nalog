<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class IncomeList implements CreatableFromArray
{
    private bool $hasMore;
    private int $currentOffset;
    private int $currentLimit;
    private IncomeListContent $content;

    private function __construct() {}

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $model = new self();

        $model->currentLimit = $data['currentLimit'];
        $model->currentOffset = $data['currentOffset'];
        $model->hasMore = $data['hasMore'];
        $model->content = IncomeListContent::createFromArray($data['content']);

        return $model;
    }

    public function getCurrentOffset(): int
    {
        return $this->currentOffset;
    }

    public function getCurrentLimit(): int
    {
        return $this->currentLimit;
    }

    public function isHasMore(): bool
    {
        return $this->hasMore;
    }

    public function getContent(): IncomeListContent
    {
        return $this->content;
    }
}
