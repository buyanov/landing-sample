FROM php:8.0.10-fpm-alpine3.14

ARG APP_ENV=production
ENV APP_ENV $APP_ENV
ARG XDEBUG_VERSION=3.0.4

RUN apk --update --virtual build-deps add \
        autoconf \
        curl-dev \
        freetype-dev \
        gcc \
        g++ \
        icu-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libtool \
        libxml2-dev \
        make \
        oniguruma-dev \
        rabbitmq-c-dev \
        pcre-dev && \
    apk add --no-cache \
        bash \
        curl \
        freetype \
        git \
        icu \
        libintl \
        libjpeg-turbo \
        libpng \
        libltdl \
        libxml2 \
        libzip-dev \
        openssh \
        postgresql-libs \
        postgresql-dev \
        rabbitmq-c \
        pcre && \
    pecl install \
        xdebug-${XDEBUG_VERSION} && \
    docker-php-ext-enable \
        xdebug && \
    docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg && \
    docker-php-ext-configure bcmath && \
    docker-php-ext-configure zip && \
    docker-php-ext-install \
        bcmath \
        curl \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        pgsql \
        pdo_pgsql \
        soap \
        sockets \
        zip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
    apk del \
        postgresql-dev \
        build-deps

RUN mkdir -p /opt/phpstorm-coverage \
    && chown www-data:www-data /opt/phpstorm-coverage

COPY . /app
WORKDIR /app

RUN set -xe \
    && if [ "$APP_ENV" = "production" ]; then export ARGS="--no-dev"; fi \
    && composer install --prefer-dist --no-scripts --no-progress --no-suggest --no-interaction $ARGS

RUN composer dump-autoload --classmap-authoritative

RUN chown www-data:www-data -R /app

USER www-data

VOLUME /app

EXPOSE 9000
