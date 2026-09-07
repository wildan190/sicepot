FROM php:8.5-fpm-bookworm

ENV DEBIAN_FRONTEND=noninteractive

# =========================================================
# System dependencies
# =========================================================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# =========================================================
# PHP Extensions
# =========================================================
RUN docker-php-ext-install pdo_pgsql

RUN docker-php-ext-install mbstring

RUN docker-php-ext-install bcmath

RUN docker-php-ext-install exif

RUN docker-php-ext-install pcntl

RUN docker-php-ext-install intl

RUN docker-php-ext-install zip

# =========================================================
# Composer
# =========================================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================================================
# Laravel
# =========================================================
WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload \
    --optimize \
    --no-dev \
    --classmap-authoritative

# =========================================================
# Laravel permissions
# =========================================================
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache \
    && chmod -R 775 \
        storage \
        bootstrap/cache

# =========================================================
# PHP production configuration
# =========================================================
RUN { \
    echo "memory_limit=512M"; \
    echo "upload_max_filesize=50M"; \
    echo "post_max_size=50M"; \
    echo "max_execution_time=120"; \
    echo "max_input_time=120"; \
} > /usr/local/etc/php/conf.d/production.ini

EXPOSE 9000

CMD ["php-fpm", "-F"]