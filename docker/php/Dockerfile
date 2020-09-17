FROM php:7.4-fpm

MAINTAINER Grzegorz Gabryel <greg@grzegab.eu>

RUN apt-get update

RUN apt-get install -y libzip-dev libicu-dev libpq-dev

RUN docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql zip intl opcache
RUN docker-php-ext-enable intl opcache

#RUN pecl install xdebug-2.9.2
#RUN docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html
