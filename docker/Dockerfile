FROM php:8.1-cli-alpine

WORKDIR /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
    && pecl install \
        pcov \
    && docker-php-ext-enable \
        pcov

COPY conf.d /usr/local/etc/php/conf.d
