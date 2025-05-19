# Use PHP 8.3 with FPM
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libpng-dev libonig-dev libxml2-dev \
    sqlite3 libsqlite3-dev gnupg \
    && docker-php-ext-install pdo pdo_sqlite zip mbstring exif pcntl bcmath gd

# Install Node.js (for Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy all files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies and build Vite assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Create SQLite file
RUN mkdir -p /data && touch /data/database.sqlite

# Expose port for PHP-FPM
EXPOSE 9000

# Final startup commands
CMD php artisan migrate --force && php artisan config:cache && php-fpm
