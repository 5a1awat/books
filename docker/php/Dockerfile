FROM php:8.1-fpm-alpine

RUN set -xe && docker-php-ext-install pdo_mysql \
    && apk update && apk add  --no-cache ffmpeg git openssh-client zip

COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer