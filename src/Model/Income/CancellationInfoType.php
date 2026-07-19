<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @phpstan-type CancellationInfoData array{
 *     operationTime: string,
 *     registerTime: string,
 *     taxPeriodId: int,
 *     comment: string,
 * }
 */
final readonly class CancellationInfoType implements CreatableFromArray
{
    public \DateTimeImmutable $operationTime;
    public \DateTimeImmutable $registerTime;
    public int $taxPeriodId;
    public string $comment;

    /**
     * @param CancellationInfoData $data
     *
     * @throws \Exception
     */
    private function __construct(array $data)
    {
        $this->operationTime = new \DateTimeImmutable($data['operationTime']);
        $this->registerTime = new \DateTimeImmutable($data['registerTime']);
        $this->taxPeriodId = $data['taxPeriodId'];
        $this->comment = $data['comment'];
    }

    /**
     * @param CancellationInfoData $data
     *
     * @throws \Exception
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
