FROM php:8.4-fpm

# Build arguments
ARG APP_KEY
ARG APP_ENV=production
ARG APP_DEBUG=false
ARG DB_HOST=172.17.50.58  # IP VPS4
ARG REDIS_HOST=172.17.50.58  # IP VPS4

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xml

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application
COPY . .

# Create .env file dengan IP VPS4
RUN echo "APP_NAME=Laravel" > .env && \
    echo "APP_ENV=${APP_ENV}" >> .env && \
    echo "APP_DEBUG=${APP_DEBUG}" >> .env && \
    echo "APP_KEY=${APP_KEY}" >> .env && \
    echo "APP_URL=http://localhost" >> .env && \
    echo "DB_CONNECTION=mysql" >> .env && \
    echo "DB_HOST=${DB_HOST}" >> .env && \
    echo "DB_PORT=3306" >> .env && \
    echo "DB_DATABASE=lks_toko" >> .env && \
    echo "DB_USERNAME=user_laravel" >> .env && \
    echo "DB_PASSWORD=admin123" >> .env && \
    echo "SESSION_DRIVER=redis" >> .env && \
    echo "CACHE_STORE=redis" >> .env && \
    echo "REDIS_CLIENT=phpredis" >> .env && \
    echo "REDIS_HOST=${REDIS_HOST}" >> .env && \
    echo "REDIS_PASSWORD=admin123" >> .env && \
    echo "REDIS_PORT=6379" >> .env && \
    echo "LOG_CHANNEL=stderr" >> .env && \
    echo "LOG_LEVEL=error" >> .env && \
    echo "FILESYSTEM_DISK=local" >> .env && \
    echo "QUEUE_CONNECTION=database" >> .env && \
    echo "MAIL_MAILER=log" >> .env

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Generate APP_KEY jika belum ada
RUN php artisan key:generate --force --no-interaction

# Cache Laravel config
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
