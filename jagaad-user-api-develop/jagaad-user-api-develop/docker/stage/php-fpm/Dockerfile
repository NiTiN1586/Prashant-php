# PHP FPM

FROM php:8.0.11-fpm-alpine

ARG USER=www-data
ARG UID=1000

RUN if [ $USER != "www-data" ]; then \
    addgroup -g ${UID} -S ${USER} \
    && adduser -D -H -u ${UID} -s /bin/bash ${USER} -G ${USER}; \
fi;

WORKDIR /var/www

USER root

RUN apk update \
    && apk add  --no-cache git mysql-client curl libmcrypt libmcrypt-dev openssh-client icu-dev \
    libxml2-dev freetype-dev libpng-dev libjpeg-turbo-dev g++ make autoconf \
    && docker-php-source extract \
    && docker-php-source delete \
    && docker-php-ext-install pdo_mysql soap intl \

    # Opcahce
    && docker-php-ext-configure opcache --enable-opcache && docker-php-ext-install opcache \

    # APCU
    && pecl install apcu && docker-php-ext-enable apcu \

    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/*

COPY ./docker/stage/php-fpm/php.ini ./usr/local/etc/php/
COPY --chown=${USER}:${USER} . /var/www/
USER ${USER}

RUN composer install --prefer-dist --no-scripts --no-autoloader && composer dump-autoload && composer clearcache

CMD ["php-fpm", "-F"]

EXPOSE 9000