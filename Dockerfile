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
ENV APP_KEY=base64:gtqmEKRtEE7Mn+/MKWRw4wxb6gBuEDHUOFHaia9whwg=

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY backend/ .

RUN cp .env.example .env

RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

CMD ["sh", "-c", "php artisan serve --host 0.0.0.0 --port $PORT"]
