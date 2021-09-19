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
