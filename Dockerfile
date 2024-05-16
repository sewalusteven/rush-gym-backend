FROM php:8.0-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo pdo_mysql zip

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.lock composer.json ./

# Install Composer dependencies
RUN composer install --no-scripts --no-autoloader

# Copy project files
COPY . .

# Generate key (if needed)
RUN php artisan key:generate

# Run composer
RUN composer install --no-dev
RUN composer dump-autoload

# Copy Nginx configuration
COPY docker/nginx/laravel.conf /etc/nginx/conf.d/default.conf

# Start Nginx and PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
