<?php

namespace Shoman4eg\Nalog\Model\Income;

use Shoman4eg\Nalog\Model\CreatableFromArray;

class IncomeType implements CreatableFromArray
{
    private string $approvedReceiptUuid;

    private function __construct($approvedReceiptUuid)
    {
        $this->approvedReceiptUuid = $approvedReceiptUuid;
    }

    public static function createFromArray(array $data): self
    {
        return new self($data['approvedReceiptUuid']);
    }

    public function getApprovedReceiptUuid(): string
    {
        return $this->approvedReceiptUuid;
    }
}
