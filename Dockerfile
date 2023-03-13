FROM php:8.2-apache AS base

RUN a2enmod rewrite && docker-php-ext-install pdo_mysql

FROM composer:2.5 AS composer

WORKDIR /var/www/html

COPY www/composer.json /var/www/html
COPY www/composer.lock /var/www/html

RUN composer install

FROM base AS prod

WORKDIR /var/www/html

COPY www /var/www/html
COPY --from=composer /var/www/html/vendor /var/www/html/vendor
