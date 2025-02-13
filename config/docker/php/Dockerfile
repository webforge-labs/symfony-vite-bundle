FROM php:8.4-fpm AS php-installed

RUN apt-get update && apt-get install -y \
    git zip unzip

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN mkdir -p /app
WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# install and cache composer dependencies
COPY composer.json ./
RUN composer install --prefer-dist --no-interaction --no-scripts
