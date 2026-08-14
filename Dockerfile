FROM php:8.2-cli-alpine

# Install system dependencies & PHP extensions for Laravel + SQLite
RUN apk add --no-cache \
    sqlite-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    icu-dev \
    oniguruma-dev \
    libzip-dev

RUN docker-php-ext-install pdo pdo_sqlite bcmath mbstring gd intl zip

# Copy Composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Install dependencies & prepare environment
RUN composer install --no-dev --optimize-autoloader
RUN cp .env.example .env
RUN php artisan key:generate
RUN touch database/database.sqlite
RUN php artisan migrate:fresh --seed --force
RUN php artisan storage:link || true
RUN touch storage/installed

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
