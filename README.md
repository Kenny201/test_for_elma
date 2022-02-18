# test_for_elma
Тестовое задание для Elma

## Задание 1:
### 1.1  Получение всех имеющихся в БД курсов:
    GET http://app.url/api/v1/rates?filter[currency]=USD // фильтр по валюте
    GET http://app.url/api/v1/rates // все курсы
### 1.2  Запрос на обмен валюты c учетом комиссии 1.5%:
    POST http://app.url/api/v1/convert
    Параметры:
    currency_from: BTC // исходная валюта
    currency_to: USD // валюта в которую конвертируем
    value: 1.00 // количество единиц исходной валюты
    source_id: 1 // id источника курса
## Задание 2:
### 2.1  Команда для добавления источника в бд (можно без поддомена api):
    sail artisan source:add
### 2.2  Команда для поиска пар:
    sail artisan crypto:find
### 2.3  Команда для добавления пар:
    sail artisan crypto:add
### 2.4  Команда для удаления пар:
    sail artisan crypto:delete
## Задание 3:
####Дамп базы для задания находится в корне проекта - файл script.sql
