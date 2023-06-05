<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class IncomeType implements CreatableFromArray
{
    private string $approvedReceiptUuid;

    private function __construct()
    {
    }

    public static function createFromArray(array $data): self
    {
        $model = new self();
        $model->approvedReceiptUuid = $data['approvedReceiptUuid'];

        return $model;
    }

    public function getApprovedReceiptUuid(): string
    {
        return $this->approvedReceiptUuid;
    }
}
