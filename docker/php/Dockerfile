FROM php:8.5-fpm-alpine

RUN apk add --no-cache \
    bash \
    mc \
    # telnet
    busybox-extras \
    tzdata \
    nodejs \
    npm

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY php.ini "$PHP_INI_DIR/conf.d/ssd.ini"
