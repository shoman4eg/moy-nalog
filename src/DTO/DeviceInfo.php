<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final readonly class DeviceInfo implements \JsonSerializable
{
    public const SOURCE_TYPE_WEB = 'WEB';
    public const APP_VERSION = '1.0.0';
    public const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';

    public function __construct(
        private string $deviceId,
        private string $type = self::SOURCE_TYPE_WEB,
        private string $appVersion = self::APP_VERSION,
        private string $userAgent = self::USER_AGENT
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'sourceType' => $this->type,
            'sourceDeviceId' => $this->deviceId,
            'appVersion' => $this->appVersion,
            'metaDetails' => ['userAgent' => $this->userAgent],
        ];
    }
}
