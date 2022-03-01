FROM php:8.1.1-fpm-alpine

ARG ENABLE_DEBUGGER
ARG XDEBUG_PATH="/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
ARG XDEBUG_PORT
ARG XDEBUG_IDE

RUN apk update \
    && apk add  --no-cache git mysql-client curl libmcrypt libmcrypt-dev openssh-client icu-dev \
    libxml2-dev freetype-dev libpng-dev libjpeg-turbo-dev g++ make autoconf \
    && docker-php-source extract \
    && docker-php-source delete \
    && docker-php-ext-install pdo_mysql soap intl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    # Amqp
    && docker-php-ext-install sockets \
    && apk add --no-cache --update rabbitmq-c-dev \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install -o -f amqp redis \
    && docker-php-ext-enable amqp redis \
    && apk del .phpize-deps \
    && rm -rf /tmp/*;

RUN if [ "$ENABLE_DEBUGGER" -eq "1" ] ; then \
    echo "You're installing environment with xdebug enabled." ; \
    pecl install xdebug ; \
    docker-php-ext-enable xdebug ; \
    echo "xdebug.idekey=$XDEBUG_IDE" >> $XDEBUG_PATH ; \
    echo "xdebug.client_port=$XDEBUG_PORT" >> $XDEBUG_PATH ; \
    echo "xdebug.log_level=0" >> $XDEBUG_PATH ; \
    echo "xdebug.mode=debug" >> $XDEBUG_PATH ; \
    echo "xdebug.start_with_request=yes" >> $XDEBUG_PATH ; \
    # For Windows and Mac users only
    echo "xdebug.client_host=host.docker.internal" >> $XDEBUG_PATH ; \
  fi

CMD ["php-fpm", "-F"]

WORKDIR /data/app

EXPOSE 9000
