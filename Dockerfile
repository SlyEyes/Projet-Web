FROM php:8.2-apache

RUN a2enmod rewrite && docker-php-ext-install pdo_mysql
