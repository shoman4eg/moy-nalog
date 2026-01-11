# Неофициальный API клиент [lknpd.nalog.ru](https://lknpd.nalog.ru/) ("Мой Налог")

[![Php version](https://img.shields.io/packagist/php-v/shoman4eg/moy-nalog?style=flat-square)](composer.json)
[![Latest Version](https://img.shields.io/github/release/shoman4eg/moy-nalog.svg?style=flat-square)](https://github.com/shoman4eg/moy-nalog/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/shoman4eg/moy-nalog.svg?style=flat-square)](https://packagist.org/packages/shoman4eg/moy-nalog)
[![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/shoman4eg/moy-nalog/master?style=flat-square)](https://scrutinizer-ci.com/g/shoman4eg/moy-nalog/?branch=master)
[![Packagist License](https://img.shields.io/packagist/l/shoman4eg/moy-nalog?style=flat-square)](LICENSE)
[![Donate](https://img.shields.io/badge/Donate-Cloudtips-6496dc?style=flat-square)](https://pay.cloudtips.ru/p/2e70e850)

Позволяет автоматизировать отправку информации о доходах для самозанятых, получать информацию о созданных чеках и удалять их. Поддерживается аутентификация по ИНН и паролю, а также по номеру телефона.

## Установка

С помощью `composer`

```bash
$ composer require shoman4eg/moy-nalog
```

Также Вам понадобится реализация виртуального пакета [`psr/http-client-implementation`](https://packagist.org/providers/psr/http-client-implementation), например (рекомендуется):

Symfony
```bash
$ composer require symfony/http-client
```

Или Guzzle
```bash
$ composer require guzzlehttp/guzzle
```

## Использование

### Настройка часового пояса
```php
// Необходимо выставить часовой пояс для корректного формирования дат в чеках
// Можно установить с помощью функции date_default_timezone_set
date_default_timezone_set('Europe/Kaliningrad');

// или через класс DateTimeImmutable, с нужным часовым поясом, перед созданием чека
$operationTime = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Kaliningrad'))
```

### Создание клиента
```php
use Shoman4eg\Nalog\ApiClient;
use Shoman4eg\Nalog\Http\ClientConfigurator;
use Shoman4eg\Nalog\Service\Generator\DeviceIdGenerator;
use Shoman4eg\Nalog\Service\Generator\StaticIdStrategy;
use Shoman4eg\Nalog\Service\Generator\IdStrategyInterface;

// Клиент с настройками по умолчанию
$apiClient = ApiClient::create();

// Создание клиента с прокси
$proxiedHttpClient = new \GuzzleHttp\Client([
    'proxy' => 'tcp://12.34.56.78:3128',
]);
$apiClient = ApiClient::createWithCustomClient($proxiedHttpClient);
// или
$apiClient = new ApiClient(clientConfigurator: new ClientConfigurator($proxiedHttpClient));

// Изменить DeviceId. По умолчанию используется PlatformIdStrategy где deviceId генерируется на основе платформы.
$apiClient = new ApiClient(deviceIdGenerator: new DeviceIdGenerator()); // По умолчанию

// Полезно использовать, если вы обслуживаете несколько самозанятых
$apiClient = new ApiClient(deviceIdGenerator: new DeviceIdGenerator(new StaticIdStrategy('example id')))

// Так же возможно написать свою реализацию
$apiClient = new ApiClient(deviceIdGenerator: new DeviceIdGenerator(new class implements IdStrategyInterface {
    public function getId(): string
    {
        return md5('example id');
    }
}));
```
### Аутентификация

При аутентификации с помощью методов `createNewAccessToken` (по ИНН и паролю) или `createNewAccessTokenByPhone` (по номеру телефона) , вместе с токеном доступа (**accessToken**), возвращается также токен обновления (**refreshToken**) с неограниченным сроком действия. Сохраните оригинальный ответ этих методов и используйте повторно в методе `authenticate`.

> При повторном использовании методов `createNewAccessToken` и `createNewAccessTokenByPhone`, предыдущий **accessToken** становится недействительным.

#### С помощью ИНН и пароля
> Если Вам нужно восстановить пароль от сервиса ["Мой налог"](https://lknpd.nalog.ru/), это возможно сделать только через ["Личный кабинет налогоплательщика"](https://lkfl2.nalog.ru/lkfl/login). Аккаунты на обоих сервисах одинаковые.
```php
use Shoman4eg\Nalog\ApiClient;
$apiClient = ApiClient::create();

try {
    // Запрос accessToken
    $accessToken = $apiClient->createNewAccessToken($username, $password);
} catch (\Shoman4eg\Nalog\Exception\Domain\UnauthorizedException $e) {
    var_dump($e->getMessage());
}

// Аутентификация с помощью accessToken
$apiClient->authenticate($accessToken);
```

#### По номеру телефона
Вариант аутентификации по номеру телефона происходит в 2 шага:
1. Запросите SMS с кодом подтверждения на номер телефона и сохраните возвращённый **challengeToken**;
2. Обменяйте номер телефона, **challengeToken** и код подтверждения на **accessToken**.

> **Внимание:** запрос нового кода подтверждения возможен только если предыдущий код истёк (2 минуты), или по предыдущему коду произошла успешная аутентификация. Повторная отправка выпущенного кода подтверждения невозможна, только одновременно с созданием нового.


#### 1. Запросите SMS с кодом подтверждения на номер телефона и сохраните возвращённый **challengeToken**:
```php
use Shoman4eg\Nalog\ApiClient;
$apiClient = ApiClient::create();

try {
    $phoneChallengeResponse = ApiClient::createPhoneChallenge('79000000000');
    /**
     * $phoneChallengeResponse = [
     *  'challengeToken' => '00000000-0000-0000-0000-000000000000',
     *  'expireDate' => '2022-11-24T00:20:19.135436Z',,
     *  'expireIn' => 120,
     *  ];
     */
} catch (\Shoman4eg\Nalog\Exception\Domain\UnauthorizedException $e) {
    var_dump($e->getMessage());
}
// Сохраните $phoneChallengeResponse['challengeToken']. Он потребуется Вам на втором шаге.
```
#### 2. Обменяйте номер телефона, **challengeToken** и код подтверждения на **accessToken**:
```php
use Shoman4eg\Nalog\ApiClient;
$apiClient = ApiClient::create();

try {
    // Запрос accessToken
    $accessToken = $apiClient->createNewAccessTokenByPhone(
        '79000000000', // Номер телефона
        '00000000-0000-0000-0000-000000000000', // challengeToken
        '123456' // Код из СМС
    );
} catch (\Shoman4eg\Nalog\Exception\Domain\UnauthorizedException $e) {
    var_dump($e->getMessage());
}

// Аутентификация с помощью accessToken
$apiClient->authenticate($accessToken);
```

### Создать чек c контрагентом по умолчанию (физ. лицо)
```php
$name = 'Предоставление информационных услуг #970/2495'; // Наименование
$amount = 1800.30; // Стоимость в рублях
$quantity = 1; // Количество
$operationTime = new DateTimeImmutable('2020-12-31 12:12:00'); // Дата продажи

// Создание чека
$createdIncome = $apiClient->income()->create(
    $name,
    $amount,
    $quantity,
    $operationTime
);

// UUID чека для операций запроса данных чека или его отмены
$receiptUuid = $createdIncome->getApprovedReceiptUuid();
```

### Создать чек с несколькими позициями
```php
$items = [
    new \Shoman4eg\Nalog\DTO\IncomeServiceItem(
        'Предоставление информационных услуг #970/2495', // Наименование
        1800.30, // Стоимость
        1 // Количество
    ),
    new \Shoman4eg\Nalog\DTO\IncomeServiceItem(
        'Предоставление информационных услуг #971/2495',
        900,
        2
    ),
    // И так далее...
];

// Дата продажи
$operationTime = new DateTimeImmutable('2020-12-31 12:12:00');

// Создание чека
$createdIncome = $apiClient->income()->createMultipleItems(
    $items,
    $operationTime
);

// UUID чека для операций запроса данных чека или его отмены
$receiptUuid = $createdIncome->getApprovedReceiptUuid();
```

### Создать чек для указанного типа контрагента (физ. лицо, юр. лицо или иностранная организация)
```php
$name = 'Предоставление информационных услуг #970/2495'; // Наименование
$amount = 1800.30; // Стоимость
$quantity = 1; // Количество
$operationTime = new DateTimeImmutable('2020-12-31 12:12:00'); // Дата продажи

// По умолчанию физ. лицо без указания контактных данных (INDIVIDUAL)
$client = new \Shoman4eg\Nalog\DTO\IncomeClient();

// Или физ. лицо с указанием контактных данных (INDIVIDUAL)
$client = new \Shoman4eg\Nalog\DTO\IncomeClient(
    '+79009000000',
    'Вася Пупкин',
    \Shoman4eg\Nalog\Enum\IncomeType::INDIVIDUAL,
    '390000000000' // ИНН физ. лица (12 символов)
);

// Или юр. лицо (ИП, ООО и т.п.) (LEGAL_ENTITY)
$client = new \Shoman4eg\Nalog\DTO\IncomeClient(
    null,
    'ИП Вася Пупкин Валерьевич',
    \Shoman4eg\Nalog\Enum\IncomeType::LEGAL_ENTITY,
    '7700000000' // ИНН юр. лица (10 символов)
);

// Или иностранная организация (FOREIGN_AGENCY)
$client = new \Shoman4eg\Nalog\DTO\IncomeClient(
    null,
    'Facebook Inc.',
    \Shoman4eg\Nalog\Enum\IncomeType::FOREIGN_AGENCY,
    '9909000000' // ИНН иностранной организации (10 символов)
);

// Создание чека
$createdIncome = $apiClient->income()->create(
    $name,
    $amount,
    $quantity,
    $operationTime,
    $client
);

// UUID чека для операций запроса данных чека или его отмены
$receiptUuid = $createdIncome->getApprovedReceiptUuid();
```

### Получить чек (скан-копия) или данные чека в JSON формате
```php
// UUID чека
$receiptUuid = "20hykdxbp8";

// Получить ссылку на чек для печати
$receipt = $apiClient->receipt()->printUrl($receiptUuid);

// Получить данные по чеку в JSON формате
$receipt = $apiClient->receipt()->json($receiptUuid);
```

### Отменить чек
```php
// UUID чека
$receiptUuid = "20hykdxbp8";

// Причина отмены: "Чек выдан ошибочно"
$comment = \Shoman4eg\Nalog\Enum\CancelCommentType::CANCEL;
// Причина отмены: "Возврат денежных средств"
$comment = \Shoman4eg\Nalog\Enum\CancelCommentType::REFUND;

// Код партнёра (по умолчанию: null)
$partnerCode = null;
// Дата совершения возврата (по умолчанию: now)
$operationTime = new \DateTimeImmutable('now');
// Дата запроса отмены чека (по умолчанию: now)
$requestTime = new \DateTimeImmutable('now');

// Отмена чека
$incomeInfo = $apiClient->income()->cancel(
    $receiptUuid,
    $comment,
    $operationTime,
    $requestTime,
    $partnerCode
);
```

### Получить информацию о текущем пользователе
```php
$apiClient->authenticate($accessToken);

$userInfo = $apiClient->user()->get();
```

### Получить информацию о необходимых платежах
```php
$apiClient->authenticate($accessToken);

$userInfo = $apiClient->tax()->get();
```

### Получить информацию о платежах
```php
$apiClient->authenticate($accessToken);

$userInfo = $apiClient->tax()->payments();
```

### Получить информацию о прошлых платежах
```php
$apiClient->authenticate($accessToken);

$userInfo = $apiClient->tax()->history();
```

## Известные проблемы
### Проблема [#47](https://github.com/shoman4eg/moy-nalog/47): Не приходят СМС для получения токена
- **Описание**: Проблемы с получением СМС-кода для авторизации
- **Решение**: Проблема связана с API сервиса nalog.ru, временная недоступность сервиса отправки СМС

### Проблема [#22](https://github.com/shoman4eg/moy-nalog/22): Авторизация по телефону или внешним ключам
- **Описание**: Невозможность авторизоваться без пароля (только по номеру телефона)
- **Решение**: Для получения пароля нужно восстановить его через веб-интерфейс "Мой налог"

### Проблема [#49](https://github.com/shoman4eg/moy-nalog/49): Токен истекает несмотря на наличие RefreshToken
- **Описание**: RefreshToken не срабатывает для получения нового токена
- **Настоящая причина**: Токен API "Мой налог" привязывается к конкретному IP-адресу или Device ID, с которого он был сгенерирован
- **Решение**: Токен нужно генерировать на нужном окружении. Токен, созданный с одного IP-адреса/Device ID, не будет работать с другого IP-адреса/Device ID (Точно сказать не могу, можно попробовать использовать в обоих окружениях StaticIdStrategy) чтобы удостовериться, что Refresh token привязывается к IP с которого сделан запрос.
- **Рекомендация**: Генерировать токен на том же сервере, с которого будут выполняться API-запросы

### Проблема [#21](https://github.com/shoman4eg/moy-nalog/21): Ошибка Could not resolve host: lknpd.nalog.ru
- **Описание**: Невозможность резолва DNS для API сервера
- **Решение**: Временная проблема на стороне сервера или DNS. Проверить настройки DNS на сервере

### Проблема [#38](https://github.com/shoman4eg/moy-nalog/38): No HTTPlug clients found
- **Описание**: Ошибка "No HTTPlug clients found"
- **Решение**: Установить любой [psr/http-client-implementation](https://packagist.org/providers/psr/http-client-implementation) совместимый клиент, например `symfony/http-client`

### Проблема: Ошибка проверки refresh токена
- **Описание**: Одна из причин может быть проблема с тем, что на одном сервере обрабатывается множество пользоавтелей и DeviceID одинаковый для всех. 
Так же можно получить ответ:
```json
{
  "code": "authentication.failed",
  "message": "Устройство <GENERATED DEVICE ID > для пользователя <INN> не может быть зарегистрировано/обновлено. Так как устройство заблокировано. Причина: Согласно обращению УОК о неправомерном доступе к ЛК",
  "additionalInfo": {}
}
```
- **Решение**: Использовать другой Device id, к примеру так:
```php
use Shoman4eg\Nalog\ApiClient;
use Shoman4eg\Nalog\Service\Generator\DeviceIdGenerator;
$apiClient = new ApiClient(deviceIdGenerator: new DeviceIdGenerator(new StaticIdStrategy('<example id>')))
```


## Использованные ресурсы
Статья на Habr: [Автоматизация для самозанятых: как интегрировать налог с IT проектом](https://habr.com/ru/post/436656/)

Реализация на JS: [alexstep/moy-nalog](https://github.com/alexstep/moy-nalog)

## Лог изменений
[Changelog](CHANGELOG.md): A complete changelog

## На кофе
Если этот проект поможет Вам сократить время разработки, вы можете угостить меня чашкой кофе :)

[Сделать пожертвование автору](https://pay.cloudtips.ru/p/2e70e850)

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
