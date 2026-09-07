FROM php:8.5-fpm-bookworm

ENV DEBIAN_FRONTEND=noninteractive

# =========================================================
# System Dependencies
# =========================================================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    pkg-config \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    && rm -rf /var/lib/apt/lists/*


# =========================================================
# PHP Extensions
# =========================================================

# PostgreSQL
RUN docker-php-ext-install pdo_pgsql

# Multibyte String
RUN docker-php-ext-install mbstring

# BCMath
RUN docker-php-ext-install bcmath

# EXIF
RUN docker-php-ext-install exif

# Process Control
RUN docker-php-ext-install pcntl

# Internationalization
RUN docker-php-ext-install intl

# ZIP
RUN docker-php-ext-install zip

# GD - required by PhpSpreadsheet
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp

RUN docker-php-ext-install gd


# =========================================================
# Composer
# =========================================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# =========================================================
# Laravel Application
# =========================================================
WORKDIR /var/www/html

COPY composer.json composer.lock ./


# =========================================================
# Composer Dependencies
# =========================================================
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts


# =========================================================
# Copy Laravel Source
# =========================================================
COPY . .


# =========================================================
# Laravel Autoloader
# =========================================================
RUN composer dump-autoload \
    --optimize \
    --no-dev \
    --classmap-authoritative


# =========================================================
# Laravel Directories
# =========================================================
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache


# =========================================================
# Laravel Permissions
# =========================================================
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache


# =========================================================
# PHP Production Configuration
# =========================================================
RUN { \
    echo "memory_limit=512M"; \
    echo "upload_max_filesize=50M"; \
    echo "post_max_size=50M"; \
    echo "max_execution_time=120"; \
    echo "max_input_time=120"; \
} > /usr/local/etc/php/conf.d/production.ini


# =========================================================
# PHP-FPM
# =========================================================
EXPOSE 9000

CMD ["php-fpm", "-F"]