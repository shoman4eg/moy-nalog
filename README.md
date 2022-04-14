# Unofficial MoyNalog API client

[![Php version](https://img.shields.io/packagist/php-v/shoman4eg/moy-nalog?style=flat-square)](composer.json)
[![Latest Version](https://img.shields.io/github/release/shoman4eg/moy-nalog.svg?style=flat-square)](https://github.com/shoman4eg/moy-nalog/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/shoman4eg/moy-nalog.svg?style=flat-square)](https://packagist.org/packages/shoman4eg/moy-nalog)
[![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/shoman4eg/moy-nalog/master?style=flat-square)](https://scrutinizer-ci.com/g/shoman4eg/moy-nalog/?branch=master)
[![Packagist License](https://img.shields.io/packagist/l/shoman4eg/moy-nalog?style=flat-square)](LICENSE)

An unofficial wrapper client for [lknpd.nalog.ru](https://lknpd.nalog.ru/) API

## Install

Via Composer

```bash
$ composer require shoman4eg/moy-nalog
```

## Usage

### Settings
```php
// If need set timezone use this
date_default_timezone_set('Europe/Kaliningrad');
// or set timezone through new DateTimeZone
$operationTime = new DateTimeImmutable('now', new DateTimeZone('Europe/Kaliningrad'))
```
### Authorization
```php
$apiClient = ApiClient::create();

// If known accessToken skip this step
$accessToken = $apiClient->createNewAccessToken($username, $password);

// Access token MUST contains all json response from method createNewAccessToken()
$accessToken = '...';
$apiClient->authenticate($accessToken);
```

### Create income with default client
```php
$name = 'Предоставление информационных услуг #970/2495';
$amount = 1800.30;
$quantity = 1;
$amount = 1;
$operationTime = new DateTimeImmutable('2020-12-31 12:12:00');
$createdIncome = $apiClient->income()->create($name, $amount, $quantity, $operationTime);
```

### Create income with custom client
```php
$name = 'Предоставление информационных услуг #970/2495';
$amount = 1800.30;
$quantity = 1;
$operationTime = new DateTimeImmutable('2020-12-31 12:12:00');

$client = new Shoman4eg\Nalog\DTO\IncomeClient(); // Default. All fields are empty IncomeType is FROM_INDIVIDUAL
// or
$client = new Shoman4eg\Nalog\DTO\IncomeClient('+79009000000', 'Вася Пупкин', \Shoman4eg\Nalog\Enum\IncomeType::INDIVIDUAL, '390000000000');
// or
$client = new Shoman4eg\Nalog\DTO\IncomeClient(null, 'Facebook Inc.', \Shoman4eg\Nalog\Enum\IncomeType::FOREIGN_AGENCY, '390000000000');
// or
$client = new Shoman4eg\Nalog\DTO\IncomeClient(null, 'ИП Вася Пупкин Валерьевич', \Shoman4eg\Nalog\Enum\IncomeType::LEGAL_ENTITY, '7700000000');
$createdIncome = $apiClient->income()->create($name, $amount, $quantity, $operationTime, $client);
```

### Cancel income
```php
$receiptUuid = "20hykdxbp8"
$comment = \Shoman4eg\Nalog\Enum\CancelCommentType::CANCEL;
$partnerCode = null; // Default null
$operationTime = new DateTimeImmutable('now'); //Default 'now'
$requestTime = new DateTimeImmutable('now'); //Default 'now'
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

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
