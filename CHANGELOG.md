<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## [0.4.2](https://github.com/shoman4eg/moy-nalog/compare/v0.4.1...v0.4.2) (2023-27-05)
### Fix
* Исправлена ошибка при установке минимальных версий зависимостей #29
* Исправлены ошибки безопасности #29
* Обновлены версии зависимостей #29

## [0.4.1](https://github.com/shoman4eg/moy-nalog/compare/v0.4.0...v0.4.1) (2023-23-05)
### Fix
* Улучшена поддержка 8.2 #28
* Добавлен возвращаемый тип к `DateTime::jsonSerialize()`  #28

## [0.4.0](https://github.com/shoman4eg/moy-nalog/compare/v0.3.1...v0.4.0) (2022-11-27)
### Features
* Добавлена аутентификация по номеру телефона и СМС #23

### Fix
* Добавлен struct_types=1
* Добавлен `final` к некоторым классам
* Исправлены ошибки psalm, phpstan
* Код-стайл

### Documentation
* Переведена на русский язык #26
* Добавлена на английском

## [0.3.1](https://github.com/shoman4eg/moy-nalog/compare/v0.3.0...v0.3.1) (2022-05-29)
### Features
* Add throw `UnauthorizedException` if server respond 401 status #19

### Documentation
* Update README with exception
* Add donation link
---

## [0.3.0](https://github.com/shoman4eg/moy-nalog/compare/v0.2.3...v0.3.0) (2022-05-15)
### Features
* Add new method for income with multiple items #14

### Documentation
* Update README with new income method

---

## [0.2.3](https://github.com/shoman4eg/moy-nalog/compare/v0.2.2...v0.2.3) (2022-04-11)
### Fix
* Change uses for avoid className conflicts
* Update README

---

## [0.2.2](https://github.com/shoman4eg/moy-nalog/compare/v0.2.1...v0.2.2) (2022-04-11)
### Fix
* Remove unused files
* Fix composer.json version
* Update .gitattributes

---

## [0.2.1](https://github.com/shoman4eg/moy-nalog/compare/v0.2.0...v0.2.1) (2022-04-11)

### Documentation
* Fix cancel income method docs
* Ошибка в документации #9

### Fix
* Type email can be nullable #10
* Fix Receipt print url #11

---

## [0.2.0](https://github.com/shoman4eg/moy-nalog/compare/v0.1.0...v0.2.0) (2022-04-09)
### Features
* Add Payment type methods
* Add Cancel income method
* Add custom client for create income
* Add custom client for create income

### Tests
* Add tests
* Add test for api client with custom access token
* Add test for create income
* Add test validation for income
* Add test for cancel income
* Add test for get paymentType

### Documentation
* Update Create api client
* Update Create income
* Update Get receipt
* Update Get user info
* Add Cancel income
* Add References

---

## [0.1.0](https://github.com/shoman4eg/moy-nalog/compare/306901e41d3ae4d4a4913f6da9606213f9d9a11d...v0.1.0) (2022-02-01)

---
Initial release
