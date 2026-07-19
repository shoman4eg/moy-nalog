<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Service\Generator;

use Shoman4eg\Nalog\Service\Generator\DeviceIdGenerator;
use Shoman4eg\Nalog\Service\Generator\IdStrategyInterface;
use Shoman4eg\Nalog\Service\Generator\PlatformIdStrategy;
use Shoman4eg\Nalog\Service\Generator\RandomIdStrategy;
use Shoman4eg\Nalog\Service\Generator\StaticIdStrategy;
use Testo\Assert;
use Testo\Test;

#[Test]
final class DeviceIdGeneratorTest
{
    public function testStaticStrategyReturnsMd5OfId(): void
    {
        Assert::same((new StaticIdStrategy(12345))->getId(), md5('12345'));
    }

    public function testPlatformStrategyContainsPhpVersion(): void
    {
        $id = (new PlatformIdStrategy())->getId();

        Assert::string($id)->contains((string)PHP_VERSION_ID);
    }

    public function testRandomStrategyReturnsRequestedByteLength(): void
    {
        Assert::same(\strlen((new RandomIdStrategy(16))->getId()), 16);
    }

    public function testRandomStrategyProducesDifferentValues(): void
    {
        Assert::notSame((new RandomIdStrategy(16))->getId(), (new RandomIdStrategy(16))->getId());
    }

    public function testGenerateIsDeterministicLowercasedAndCapped(): void
    {
        $id = (new DeviceIdGenerator(new StaticIdStrategy(12345)))->generate();

        Assert::same(\strlen($id), 21);
        Assert::same($id, strtolower($id));
        Assert::true(preg_match('/^[a-z0-9]+$/', $id) === 1);
        // Same strategy input must yield the same device id.
        Assert::same((new DeviceIdGenerator(new StaticIdStrategy(12345)))->generate(), $id);
    }

    public function testGenerateRespectsCustomLength(): void
    {
        Assert::same(\strlen((new DeviceIdGenerator(new StaticIdStrategy(12345), length: 10))->generate()), 10);
    }

    public function testGenerateWithoutLowercaseKeepsOriginalCase(): void
    {
        $raw = (new DeviceIdGenerator(new StaticIdStrategy(12345), lowercased: false))->generate();
        $lowercased = (new DeviceIdGenerator(new StaticIdStrategy(12345)))->generate();

        Assert::same($lowercased, strtolower($raw));
    }

    public function testGenerateAcceptsCustomStrategy(): void
    {
        $generator = new DeviceIdGenerator(new class implements IdStrategyInterface {
            public function getId(): string
            {
                return 'custom-source-id';
            }
        });

        Assert::false(empty($generator->generate()));
    }

    public function testGenerateStripsBase64SpecialCharacters(): void
    {
        $id = (new DeviceIdGenerator(new StaticIdStrategy(12345)))->generate();

        Assert::string($id)->notContains('+');
        Assert::string($id)->notContains('/');
        Assert::string($id)->notContains('=');
    }
}
