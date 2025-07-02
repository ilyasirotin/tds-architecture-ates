FROM php:8.2.16-fpm-alpine

ENV XDEBUG_VERSION 3.3.1

ENV MUSL_LOCPATH /usr/share/i18n/locales/musl

RUN apk add --update --no-cache \
        bash \
        pcre-dev $PHPIZE_DEPS \
        fcgi \
        git \
        freetype \
        libpng \
        libjpeg-turbo \
        freetype-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        libzip-dev \
        postgresql-dev \
        zip \
        linux-headers \
        libsodium-dev \
        icu-dev \
        icu-data-full \
        rabbitmq-c-dev \
        librdkafka-dev \
        supervisor \
        python3 \
    && git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    && docker-php-ext-configure \
        xdebug --enable-xdebug-dev \
    && docker-php-ext-configure \
        pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure \
        zip \
    && docker-php-ext-configure \
        gd --with-jpeg --with-freetype \
    && docker-php-ext-configure \
        intl \
    && NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-install -j${NPROC} \
        gd \
        xdebug \
        zip \
        pdo \
        pdo_pgsql \
        intl \
        sodium \
        opcache \
    && pecl install redis amqp rdkafka \
    && docker-php-ext-enable \
        gd \
        xdebug \
        zip \
        pdo \
        pdo_pgsql \
        intl \
        sodium \
        opcache \
        redis.so \
        amqp \
        rdkafka \
    && apk del --no-cache \
        git

RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

COPY ./php-fpm/conf.d /usr/local/etc/php/conf.d

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

RUN addgroup -g 1000 dev && adduser -u 1000 -G dev -s /bin/sh -D dev

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash && apk add symfony-cli

WORKDIR /app

USER dev
