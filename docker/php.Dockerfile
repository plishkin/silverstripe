FROM php:8.1-fpm

RUN apt-get update && apt-get install -y nano mc \
    wget git zip curl sendmail \
    openssl libssl-dev procps htop

RUN apt-get install -y libonig-dev libpq-dev && docker-php-ext-install mbstring
RUN apt-get install -y libxml2-dev && docker-php-ext-install xml
RUN apt-get install -y libcurl4-openssl-dev && docker-php-ext-install curl
RUN apt-get install -y libzip-dev && docker-php-ext-install zip

RUN docker-php-ext-install intl

RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install gd

RUN docker-php-ext-install mysqli pdo_mysql

RUN pecl install swoole
COPY docker/configs/swoole.ini /usr/local/etc/php/conf.d/swoole.ini

# Xdebug
ARG INSTALL_XDEBUG=false
COPY docker/configs/xdebug.ini /tmp/xdebug.ini
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug && mv /tmp/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini; \
    fi;

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

