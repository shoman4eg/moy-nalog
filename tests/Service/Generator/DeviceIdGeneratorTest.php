<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Service\Generator;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Shoman4eg\Nalog\Service\Generator\DeviceIdGenerator;
use Shoman4eg\Nalog\Service\Generator\IdStrategyInterface;
use Shoman4eg\Nalog\Service\Generator\PlatformIdStrategy;
use Shoman4eg\Nalog\Service\Generator\RandomIdStrategy;
use Shoman4eg\Nalog\Service\Generator\StaticIdStrategy;

/**
 * @internal
 */
#[CoversNothing]
final class DeviceIdGeneratorTest extends TestCase
{
    public function testStaticStrategyReturnsMd5OfId(): void
    {
        self::assertSame(md5('12345'), (new StaticIdStrategy(12345))->getId());
    }

    public function testPlatformStrategyContainsPhpVersion(): void
    {
        $id = (new PlatformIdStrategy())->getId();

        self::assertStringContainsString((string)PHP_VERSION_ID, $id);
    }

    public function testRandomStrategyReturnsRequestedByteLength(): void
    {
        self::assertSame(16, \strlen((new RandomIdStrategy(16))->getId()));
    }

    public function testRandomStrategyProducesDifferentValues(): void
    {
        self::assertNotSame(
            (new RandomIdStrategy(16))->getId(),
            (new RandomIdStrategy(16))->getId()
        );
    }

    public function testGenerateIsDeterministicLowercasedAndCapped(): void
    {
        $id = (new DeviceIdGenerator(new StaticIdStrategy(12345)))->generate();

        self::assertSame(21, \strlen($id));
        self::assertSame(strtolower($id), $id);
        self::assertMatchesRegularExpression('/^[a-z0-9]+$/', $id);
        // Same strategy input must yield the same device id.
        self::assertSame($id, (new DeviceIdGenerator(new StaticIdStrategy(12345)))->generate());
    }

    public function testGenerateRespectsCustomLength(): void
    {
        self::assertSame(10, \strlen((new DeviceIdGenerator(new StaticIdStrategy(12345), length: 10))->generate()));
    }

    public function testGenerateWithoutLowercaseKeepsOriginalCase(): void
    {
        $raw = (new DeviceIdGenerator(new StaticIdStrategy(12345), lowercased: false))->generate();
        $lowercased = (new DeviceIdGenerator(new StaticIdStrategy(12345)))->generate();

        self::assertSame(strtolower($raw), $lowercased);
    }

    public function testGenerateAcceptsCustomStrategy(): void
    {
        $generator = new DeviceIdGenerator(new class implements IdStrategyInterface {
            public function getId(): string
            {
                return 'custom-source-id';
            }
        });

        self::assertNotEmpty($generator->generate());
    }

    public function testGenerateStripsBase64SpecialCharacters(): void
    {
        $id = (new DeviceIdGenerator(new StaticIdStrategy(12345)))->generate();

        self::assertStringNotContainsString('+', $id);
        self::assertStringNotContainsString('/', $id);
        self::assertStringNotContainsString('=', $id);
    }
}
