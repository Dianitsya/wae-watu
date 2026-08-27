FROM php:8.2-cli

# Install system dependencies & PHP extensions for PostgreSQL, MySQL, MBString, Zip
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip

ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy Composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy backend files to container workdir
COPY backend/ .

# Copy public images
COPY images/ ./public/images/

# Prepare .env if missing
RUN if [ -f .env.example ] && [ ! -f .env ]; then cp .env.example .env; fi

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Prepare storage permissions
RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Startup command
CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --force && php artisan serve --host 0.0.0.0 --port $PORT"]
