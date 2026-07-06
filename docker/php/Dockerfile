FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    libzip-dev \
    zip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    pkg-config \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        gd \
        intl


RUN pecl install redis && docker-php-ext-enable redis


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

LABEL authors="HeadSplit"

CMD ["php-fpm"]
