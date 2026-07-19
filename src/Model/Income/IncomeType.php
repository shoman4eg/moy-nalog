<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class IncomeType implements CreatableFromArray
{
    public string $approvedReceiptUuid;

    /**
     * @param array{approvedReceiptUuid: string} $data
     */
    private function __construct(array $data)
    {
        $this->approvedReceiptUuid = $data['approvedReceiptUuid'];
    }

    /**
     * @param array{approvedReceiptUuid: string} $data
     */
    public static function createFromArray(array $data): self
    {
        return new self($data);
    }
}
