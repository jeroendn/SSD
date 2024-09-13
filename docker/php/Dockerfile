FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    mc \
    # telnet
    busybox-extras \
    nodejs \
    npm

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
