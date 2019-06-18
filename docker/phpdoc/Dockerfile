FROM php:7.3

COPY conf.d/zzz.ini /usr/local/etc/php/conf.d/

RUN apt-get update \
    && apt-get install -yq graphviz curl git libicu-dev libicu57 zlib1g-dev libxslt1-dev libzip-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install -j$(nproc) intl zip xsl

WORKDIR /data
VOLUME /data

ENV APP_ENV=prod
RUN cd /opt \
    && git clone https://github.com/phpDocumentor/phpDocumentor2.git phpdoc \
    && cd phpdoc \
    && git checkout v2.9.0 \
    && curl -O https://getcomposer.org/download/1.8.6/composer.phar \
    && chmod +x bin/phpdoc \
    && php composer.phar install --prefer-dist -o --no-interaction --no-dev \
    && rm composer.phar

ENTRYPOINT ["/opt/phpdoc/bin/phpdoc"]
