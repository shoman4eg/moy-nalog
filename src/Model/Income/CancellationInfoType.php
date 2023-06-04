<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class CancellationInfoType implements CreatableFromArray
{
    private \DateTimeImmutable $operationTime;
    private \DateTimeImmutable $registerTime;
    private int $taxPeriodId;
    private string $comment;

    private function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        $model = new self();
        $model->operationTime = new \DateTimeImmutable($data['operationTime']);
        $model->registerTime = new \DateTimeImmutable($data['registerTime']);
        $model->taxPeriodId = $data['taxPeriodId'];
        $model->comment = $data['comment'];

        return $model;
    }

    public function getOperationTime(): \DateTimeImmutable
    {
        return $this->operationTime;
    }

    public function getRegisterTime(): \DateTimeImmutable
    {
        return $this->registerTime;
    }

    public function getTaxPeriodId(): int
    {
        return $this->taxPeriodId;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}
