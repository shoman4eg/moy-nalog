<?php
declare(strict_types=1);

namespace Shoman4eg\Nalog\Tests\Api;

use Shoman4eg\Nalog\Tests\ApiTestCase;
use Testo\Assert;
use Testo\Codecov\CoversNothing;
use Testo\Test;

/**
 * @internal
 */
#[Test]
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

        Assert::null($response->lastName);
        Assert::same($response->id, $data['id']);
        Assert::same($response->displayName, $data['displayName']);
        Assert::same($response->email, $data['email']);
        Assert::same($response->phone, $data['phone']);
        Assert::same($response->inn, $data['inn']);
        Assert::same($response->snils, $data['snils']);
        Assert::same($response->avatarExists, $data['avatarExists']);
        Assert::instanceOf($response->initialRegistrationDate, \DateTimeImmutable::class);
        Assert::same($response->initialRegistrationDate->getTimestamp(), strtotime($data['initialRegistrationDate']));
        Assert::instanceOf($response->firstReceiptRegisterTime, \DateTimeImmutable::class);
        Assert::same($response->firstReceiptRegisterTime->getTimestamp(), strtotime($data['firstReceiptRegisterTime']));
        Assert::null($response->firstReceiptCancelTime);
        Assert::null($response->registerAvailable);
        Assert::same($response->login, $data['login']);
        Assert::same($response->pfrUrl, $data['pfrUrl']);
        Assert::same($response->status, $data['status']);
        Assert::same($response->hideCancelledReceipt, $data['hideCancelledReceipt']);
    }
}
