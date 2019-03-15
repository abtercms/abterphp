FROM php:7.2-fpm-alpine

COPY config/php.ini /usr/local/etc/php/

RUN apk upgrade --update && apk add \
        coreutils \
        autoconf \
        build-base \
        freetype-dev \
        libjpeg-turbo-dev \
        libltdl \
        libmcrypt-dev \
        libpng-dev \
        icu-dev \
        libxml2-dev \
        libxslt-dev \
        libzip-dev \
        git \
        libsodium-dev \
    && docker-php-ext-install -j$(nproc) iconv intl pdo_mysql soap xsl zip bcmath \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN pecl install redis-4.1.1 \
    && pecl install xdebug-2.6.1 \
    && pecl install libsodium-2.0.20 \
    && pecl install mcrypt-1.0.2 \
    && docker-php-ext-enable redis xdebug
