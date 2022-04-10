<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use Shoman4eg\Nalog\Tests\ApiTestCase;

/**
 * @author Artem Dubinin <artem@dubinin.me>
 *
 * @internal
 * @coversNothing
 */
class UserTest extends ApiTestCase
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

        self::assertNull($response->getLastName());
        self::assertSame(1000000, $response->getId());
        self::assertSame($data['displayName'], $response->getDisplayName());
        self::assertSame($data['email'], $response->getEmail());
        self::assertSame($data['phone'], $response->getPhone());
        self::assertSame($data['inn'], $response->getInn());
        self::assertSame($data['snils'], $response->getSnils());
        self::assertSame($data['avatarExists'], $response->isAvatarExists());
        self::assertInstanceOf(\DateTimeImmutable::class, $response->getInitialRegistrationDate());
        self::assertSame(
            strtotime($data['initialRegistrationDate']),
            $response->getInitialRegistrationDate()->getTimestamp()
        );
        self::assertSame(
            strtotime($data['firstReceiptRegisterTime']),
            $response->getFirstReceiptRegisterTime()->getTimestamp()
        );
        self::assertNull($response->getFirstReceiptCancelTime());
        self::assertNull($response->getRegisterAvailable());
        self::assertSame($data['login'], $response->getLogin());
        self::assertSame($data['pfrUrl'], $response->getPfrUrl());
        self::assertSame($data['status'], $response->getStatus());
        self::assertSame($data['hideCancelledReceipt'], $response->isHideCancelledReceipt());
    }
}
