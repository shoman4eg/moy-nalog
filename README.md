# Unofficial MoyNalog API client

[![Latest Version](https://img.shields.io/github/release/shoman4eg/moy-nalog.svg?style=flat-square)](https://github.com/shoman4eg/moy-nalog/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/shoman4eg/moy-nalog.svg?style=flat-square)](https://packagist.org/packages/shoman4eg/moy-nalog)

An unofficial wrapper client for lknpd.nalog.ru API

## Install

Via Composer

``` bash
$ composer require shoman4eg/moy-nalog
```

## Usage

``` php
$apiClient = ApiClient::create();
$accessToken = $apiClient->createNewAccessToken($username, $password);
$apiClient->authenticate($accessToken);
$name = 'Предоставление информационных услуг #970/2495';
$quantity = 1;
$amount = 1;
$operationTime = new DateTimeImmutable('2020-12-31 12:12:00');
$createdIncome = $apiClient->income()->create($name, $amount, $quantity, $operationTime);
```

## Changelog
[Changelog](CHANGELOG.md): A complete changelog

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
