FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    $PHPIZE_DEPS \
    zip \
    zlib1g-dev \
    libzip-dev \
    tzdata \
    # clean up
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN pecl install redis

RUN docker-php-ext-enable redis \
    && docker-php-ext-install zip pdo pdo_mysql opcache
