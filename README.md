INSTALLATION
------------

### Установка и запуск

Выполнить
~~~
docker compose up -d
~~~

Создаем БД

~~~
CREATE DATABASE books CHARACTER SET utf8mb4;
~~~

Далее заходим в контейнер

~~~
docker exec -it books-php-1 sh
~~~

Выполняем установку

~~~
composer install
~~~

И выполняем миграцию

~~~
./yii migrate
~~~

И заходим по URL:

~~~
http://localhost:8000
~~~

