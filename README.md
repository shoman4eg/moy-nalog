# Unofficial MoyNalog API client

[![Php version](https://img.shields.io/packagist/php-v/shoman4eg/moy-nalog?style=flat-square)](composer.json)
[![Latest Version](https://img.shields.io/github/release/shoman4eg/moy-nalog.svg?style=flat-square)](https://github.com/shoman4eg/moy-nalog/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/shoman4eg/moy-nalog.svg?style=flat-square)](https://packagist.org/packages/shoman4eg/moy-nalog)
[![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/shoman4eg/moy-nalog/master?style=flat-square)](https://scrutinizer-ci.com/g/shoman4eg/moy-nalog/?branch=master)
[![Packagist License](https://img.shields.io/packagist/l/shoman4eg/moy-nalog?style=flat-square)](LICENSE)
[![Donate](https://img.shields.io/badge/Donate-Tinkoff-yellow?style=flat-square)](https://www.tinkoff.ru/cf/7rZnC7N4bOO)

An unofficial wrapper client for [lknpd.nalog.ru](https://lknpd.nalog.ru/) API

## Install

Via Composer

```bash
$ composer require shoman4eg/moy-nalog
```

Also you need one of packages suggests `psr/http-client-implementation`

Recommends `symfony/http-client` or `guzzlehttp/guzzle`

## Usage

### Settings
```php
// If need set timezone use this
date_default_timezone_set('Europe/Kaliningrad');
// or set timezone through new DateTimeZone
$operationTime = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Kaliningrad'))
```
### Authorization by INN & password
```php
use Shoman4eg\Nalog\ApiClient;

$apiClient = ApiClient::create();

// If known accessToken skip this step
try {
    $accessToken = $apiClient->createNewAccessToken($username, $password);
} catch (\Shoman4eg\Nalog\Exception\Domain\UnauthorizedException $e) {
    var_dump($e->getMessage());
}

$apiClient->authenticate($accessToken);
```

### Authorization by phone number
Authorization by phone number takes place in two steps.
You need to request authorization by phone, temporarily save the returned challenge token, receive an SMS with a confirmation code, and then pass the phone, the challenge token and the confirmation code from the SMS by a second request.

**Please note:** there is a limit for sending SMS with confirmation code (one SMS every 1-2 minutes).

#### 1. Send an SMS with a confirmation code to your phone number and temporarily save the challenge token:
```php
use Shoman4eg\Nalog\ApiClient;

$apiClient = ApiClient::create();

try {
    $response = $apiClient->createPhoneChallenge('79999999999');
    
    //$response: Array(
    //  [challengeToken] => 00000000-0000-0000-0000-000000000000
    //  [expireDate] => 2022-11-24T00:20:19.135436Z
    //  [expireIn] => 120
    //)
} catch (\Shoman4eg\Nalog\Exception\Domain\UnauthorizedException $e) {
    var_dump($e->getMessage());
}

//Save $response['challengeToken'] until you get the confirmation code from the SMS. You need it for the second step.
```
#### 2. Exchange your phone number, challenge token and code from SMS for the access token:
```php
use Shoman4eg\Nalog\ApiClient;

$apiClient = ApiClient::create();

try {
    $accessToken = $apiClient->createNewAccessTokenByPhone(
    	'79999999999',
    	'00000000-0000-0000-0000-000000000000',
    	'111111'
    );
} catch (\Shoman4eg\Nalog\Exception\Domain\UnauthorizedException $e) {
    var_dump($e->getMessage());
}

$apiClient->authenticate($accessToken);
```

### Create income with default client
```php
$name = 'Предоставление информационных услуг #970/2495';
$amount = 1800.30;
$quantity = 1;
$operationTime = new DateTimeImmutable('2020-12-31 12:12:00');
$createdIncome = $apiClient->income()->create($name, $amount, $quantity, $operationTime);
```

### Create income with multiple items
```php
$name = 'Предоставление информационных услуг #970/2495';
$items = [
    new \Shoman4eg\Nalog\DTO\IncomeServiceItem($name, $amount = 1800.30, $quantity = 1),
    new \Shoman4eg\Nalog\DTO\IncomeServiceItem($name, $amount = 900, $quantity = 2),
    new \Shoman4eg\Nalog\DTO\IncomeServiceItem($name, $amount = '1399.99', $quantity = 3),
];
$operationTime = new DateTimeImmutable('2020-12-31 12:12:00');
$createdIncome = $apiClient->income()->createMultipleItems($items, $operationTime);
```

### Create income with custom client
```php
$name = 'Предоставление информационных услуг #970/2495';
$amount = 1800.30;
$quantity = 1;
$operationTime = new \DateTimeImmutable('2020-12-31 12:12:00');

$client = new \Shoman4eg\Nalog\DTO\IncomeClient(); // Default. All fields are empty IncomeType is FROM_INDIVIDUAL
// or
$client = new \Shoman4eg\Nalog\DTO\IncomeClient('+79009000000', 'Вася Пупкин', \Shoman4eg\Nalog\Enum\IncomeType::INDIVIDUAL, '390000000000');
// or
$client = new \Shoman4eg\Nalog\DTO\IncomeClient(null, 'Facebook Inc.', \Shoman4eg\Nalog\Enum\IncomeType::FOREIGN_AGENCY, '390000000000');
// or
$client = new \Shoman4eg\Nalog\DTO\IncomeClient(null, 'ИП Вася Пупкин Валерьевич', \Shoman4eg\Nalog\Enum\IncomeType::LEGAL_ENTITY, '7700000000');
$createdIncome = $apiClient->income()->create($name, $amount, $quantity, $operationTime, $client);
```

### Cancel income
```php
$receiptUuid = "20hykdxbp8"
$comment = \Shoman4eg\Nalog\Enum\CancelCommentType::CANCEL;
$partnerCode = null; // Default null
$operationTime = new \DateTimeImmutable('now'); //Default 'now'
$requestTime = new \DateTimeImmutable('now'); //Default 'now'
$incomeInfo = $apiClient->income()->cancel($receiptUuid, $comment, $operationTime, $requestTime, $partnerCode);
```

### Create Invoice
```php
// todo
```

### Cancel Invoice
```php
// todo
```

### Change payment type in Invoice
```php
// todo
```

### Get user info
```php
$userInfo = $apiClient->user()->get();
```

### Get receipt info
```php
// $receiptUuid = $createdincome->getApprovedReceiptUuid();

// Get print url
$receipt = $apiClient->receipt()->printUrl($receiptUuid);

// Json data
$receipt = $apiClient->receipt()->json($receiptUuid);
```

## References
[Автоматизация для самозанятых: как интегрировать налог с IT проектом](https://habr.com/ru/post/436656/)

JS lib [alexstep/moy-nalog](https://github.com/alexstep/moy-nalog)

## Changelog
[Changelog](CHANGELOG.md): A complete changelog

## Donation
If this project help you reduce time to develop, you can give me a cup of coffee :)

[Link to donate](https://www.tinkoff.ru/cf/7rZnC7N4bOO)

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
