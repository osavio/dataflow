FROM php:8.4-fpm-alpine

ARG UID=1000

RUN apk add --no-cache \
        libpq \
        libzip \
        git \
        unzip \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        linux-headers \
        postgresql-dev \
        libzip-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        bcmath \
        pcntl \
        sockets \
        zip \
        opcache \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

RUN adduser -D -u ${UID} app

WORKDIR /var/www/html

USER app

EXPOSE 9000

CMD ["php-fpm"]