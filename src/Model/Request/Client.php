<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Model\Request;

final class Client implements \JsonSerializable
{
    private const INCOME_TYPE_INDIVIDUAL = 'FROM_INDIVIDUAL';

    private ?string $contactPhone;
    private ?string $displayName;
    private string $incomeType;
    private ?string $inn;

    public function __construct(
        ?string $contactPhone = null,
        ?string $displayName = null,
        string $incomeType = self::INCOME_TYPE_INDIVIDUAL,
        ?string $inn = null
    ) {
        $this->contactPhone = $contactPhone;
        $this->displayName = $displayName;
        $this->incomeType = $incomeType;
        $this->inn = $inn;
    }

    public function jsonSerialize(): array
    {
        return [
            'contactPhone' => $this->contactPhone,
            'displayName' => $this->displayName,
            'incomeType' => $this->incomeType,
            'inn' => $this->inn,
        ];
    }
}
