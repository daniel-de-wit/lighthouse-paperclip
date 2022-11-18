FROM php:8.1-cli-alpine

WORKDIR /var/www/html

COPY conf.d /usr/local/etc/php/conf.d
COPY --from=composer /usr/bin/composer /usr/bin/composer
