<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\DTO;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 */
final class DeviceInfo implements \JsonSerializable
{
    public const SOURCE_TYPE_WEB = 'WEB';
    public const APP_VERSION = '1.0.0';
    public const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';

    private string $type;
    private string $deviceId;
    private string $appVersion;
    private string $userAgent;

    public function __construct(
        string $deviceId,
        string $type = self::SOURCE_TYPE_WEB,
        string $appVersion = self::APP_VERSION,
        string $userAgent = self::USER_AGENT
    ) {
        $this->type = $type;
        $this->deviceId = $deviceId;
        $this->appVersion = $appVersion;
        $this->userAgent = $userAgent;
    }

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
