FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libjpeg-dev libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install PHP dependencies if composer.json is present
RUN if [ -f composer.json ]; then composer install --no-interaction --prefer-dist --optimize-autoloader; fi

# Ensure writable directories exist (if this is a real Laravel app)
RUN mkdir -p storage bootstrap/cache || true
RUN chown -R www-data:www-data /var/www/html || true

EXPOSE 8000

# Default command: serve the app (works with Laravel's artisan)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
