FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath intl zip

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY backend/ .

RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

CMD ["sh", "-c", "php artisan key:generate --force && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host 0.0.0.0 --port $PORT"]
