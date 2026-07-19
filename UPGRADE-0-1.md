# Upgrade Guide

## v0.x → v1.0

### Требования

PHP **8.2+** (было `>=7.4`).

---

### Enums вместо классов-констант

Все классы в `Enum\` стали нативными PHP-перечислениями:
`BuyerType`, `CancelCommentType`, `IncomeType`, `PaymentType`, `ReceiptType`.

```php
// Было
BuyerType::PERSON          // string 'PERSON'

// Стало
BuyerType::PERSON          // BuyerType (enum instance)
BuyerType::PERSON->value   // string 'PERSON'
```

Метод `::all()` удалён:

```php
// Было
BuyerType::all() // ['PERSON', 'COMPANY', 'FOREIGN_AGENCY']

// Стало
BuyerType::cases()                          // [BuyerType::PERSON, ...]
array_column(BuyerType::cases(), 'value')   // ['PERSON', ...]
```

---

### Income::list() — параметры buyerType и receiptType

```php
// Было
$client->income()->list(buyerType: 'PERSON', receiptType: 'REGISTERED');

// Стало
use Shoman4eg\Nalog\Enum\BuyerType;
use Shoman4eg\Nalog\Enum\ReceiptType;

$client->income()->list(buyerType: BuyerType::PERSON, receiptType: ReceiptType::REGISTERED);
```

---

### Income::cancel() — параметр comment

```php
// Было
use Shoman4eg\Nalog\Enum\CancelCommentType;
$client->income()->cancel($uuid, CancelCommentType::REFUND); // string

// Стало — передавайте enum или строку
$client->income()->cancel($uuid, CancelCommentType::REFUND); // enum instance
```

---

### Response-модели — геттеры удалены, свойства стали публичными

Все модели ответов теперь имеют публичные `readonly` свойства вместо геттеров.
Тривиальные геттеры (`getApprovedReceiptUuid()`, `getPhone()`, `getRegionName()`
и т.п.) удалены — обращайтесь к свойствам напрямую.

```php
// Было
$income->getApprovedReceiptUuid(); // string
$user->getPhone();                 // string

// Стало
$income->approvedReceiptUuid;      // string
$user->phone;                      // string
```

Это касается и составных геттеров:

```php
// Было
$incomeList->getContent();   // IncomeListContent
$item->isCancelled();        // bool

// Стало
$incomeList->content;        // IncomeListContent
$item->cancelled;            // bool
```

Затронутые пространства имён моделей:
`Model\Income\*`, `Model\Tax\*`, `Model\PaymentType\*`, `Model\User\UserType`.

---

### IncomeClient / InvoiceClient — параметр incomeType

```php
// Было
new IncomeClient(incomeType: IncomeType::INDIVIDUAL); // string 'FROM_INDIVIDUAL'

// Стало
use Shoman4eg\Nalog\Enum\IncomeType;
new IncomeClient(incomeType: IncomeType::INDIVIDUAL); // enum instance
```

---

### IncomeClient / InvoiceClient — геттеры удалены, свойства стали публичными

`IncomeClient` (и `InvoiceClient`) теперь `final readonly` классы с публичными
свойствами. У `IncomeClient` геттеры `getIncomeType()`, `getInn()`,
`getDisplayName()` удалены — обращайтесь к свойствам напрямую. Тип `incomeType`
изменился со `string` на enum `IncomeType`.

```php
// Было
$incomeClient->getIncomeType();  // string 'FROM_INDIVIDUAL'
$incomeClient->getInn();         // ?string
$incomeClient->getDisplayName(); // ?string

// Стало
$incomeClient->incomeType;        // IncomeType::INDIVIDUAL (enum)
$incomeClient->incomeType->value; // 'FROM_INDIVIDUAL'
$incomeClient->inn;               // ?string
$incomeClient->displayName;       // ?string
```

---

### Income::create() / Invoice::create() — параметр quantity

Параметр `$quantity` теперь принимает только `float|int`. Передача строки вызовет `TypeError`.

```php
// Было (работало)
$client->income()->create('name', 100, '2');

// Стало — только числовые типы
$client->income()->create('name', 100, 2);
```
