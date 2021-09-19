# Landing + Activity JRPC server

Весь проект "завернут" в docker-compose файл для локальной разработки и тестов

```bash
git clone https://github.com/buyanov/landing-sample.git
cd landing-sample
cp .env.example .env
```

Чтобы запустить проект, нужно выполнить ```make up```

Перед началом работы необходимо установить зависимости с помощью команды ```make install```
далее запустить миграции, чтобы восстановить структуру базы данных ```make db```

Чтобы запустить тесты с покрытием нужно настроить phpstrom на работу с PHP внутри контейнера web и web_test

![PHP settings](https://github.com/buyanov/landing-sample/blob/master/docs/php-settings.png?raw=true)
![Tests settings](https://github.com/buyanov/landing-sample/blob/master/docs/test-config.png?raw=true)

Затем нужно создать тестовую базу данных и наполнить ее ```make db-test```

![Run tests with coverage](https://github.com/buyanov/landing-sample/blob/master/docs/run-tests.png?raw=true)

В результате получим запуск тестов в отдельном контейнере

## Особенности и описание решения

- "Landing" сделан на laravel (php 8, laravel 8)
- Json-RPC сервер я решил написать на GO (на PHP тоже можно, но в проде я бы такое не стал использовать)
- Посмотреть схему можно по адресу http://localhost:8080/jrpc/debug
- Внутри Landing есть JRPC клиент, который обращается к серверу по внутренней сети, я его нарочно оставил чтобы можно было посмотреть дебаг
- В основе клиента лежит пакет HTTP-PLUG, запросы на запись выполняются асинхронно, чтобы не повлиять на производительность
- В проекте настроена конфигурация PHP-CS-FIXER и PHPStan (ошибки не исправил, в бою использую самые строгие правила, которые и настроил)
- В статистику пишутся все обращения, потому что использован глобальный Middleware
- Базу для хранения статистики взял ClickHouse (в postgresql такое хранить не хочется)
- Создан Makefile для удобства ```make``` чтобы посмотреть все команды 
