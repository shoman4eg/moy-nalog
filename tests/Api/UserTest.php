<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use PHPUnit\Framework\Attributes\CoversNothing;
use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @internal
 */
#[CoversNothing]
final class UserTest extends ApiTestCase
{
    public function testGet(): void
    {
        $data = [
            'lastName' => null,
            'id' => 1000000,
            'displayName' => 'Surname Name MiddleName',
            'middleName' => null,
            'email' => 'email@example.com',
            'phone' => '79000000000',
            'inn' => '300000000000',
            'snils' => '000-000-000 00',
            'avatarExists' => false,
            'initialRegistrationDate' => '2021-01-27T22:38:30.057957Z',
            'registrationDate' => '2021-01-27T22:38:30.057957Z',
            'firstReceiptRegisterTime' => '2021-03-11T13:37:23Z',
            'firstReceiptCancelTime' => null,
            'hideCancelledReceipt' => false,
            'registerAvailable' => null,
            'status' => 'ACTIVE',
            'restrictedMode' => false,
            'pfrUrl' => 'https://es.pfrf.ru',
            'login' => '100000000000',
        ];
        $this->appendSuccessJson($data);

        $response = $this->client->user()->get();

        self::assertNull($response->lastName);
        self::assertSame($data['id'], $response->id);
        self::assertSame($data['displayName'], $response->displayName);
        self::assertSame($data['email'], $response->email);
        self::assertSame($data['phone'], $response->phone);
        self::assertSame($data['inn'], $response->inn);
        self::assertSame($data['snils'], $response->snils);
        self::assertSame($data['avatarExists'], $response->avatarExists);
        self::assertInstanceOf(\DateTimeImmutable::class, $response->initialRegistrationDate);
        self::assertSame(
            strtotime($data['initialRegistrationDate']),
            $response->initialRegistrationDate->getTimestamp()
        );
        self::assertInstanceOf(\DateTimeImmutable::class, $response->firstReceiptRegisterTime);
        self::assertSame(
            strtotime($data['firstReceiptRegisterTime']),
            $response->firstReceiptRegisterTime->getTimestamp()
        );
        self::assertNull($response->firstReceiptCancelTime);
        self::assertNull($response->registerAvailable);
        self::assertSame($data['login'], $response->login);
        self::assertSame($data['pfrUrl'], $response->pfrUrl);
        self::assertSame($data['status'], $response->status);
        self::assertSame($data['hideCancelledReceipt'], $response->hideCancelledReceipt);
    }
}
