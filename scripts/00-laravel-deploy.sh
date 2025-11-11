#!/usr/bin/env bash
set -e  # Exit on error

echo "Running composer install..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --working-dir=/var/www/html

echo "Generating application key if not set..."
php artisan key:generate --force || true

echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Verifying build assets..."
if [ -d "/var/www/html/public/build" ]; then
    echo "✓ Build directory exists"
    ls -la /var/www/html/public/build/
else
    echo "✗ WARNING: Build directory not found!"
fi

echo "Setting permissions..."
chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

if [ -d "/var/www/html/public/build" ]; then
    chown -R nginx:nginx /var/www/html/public/build
    chmod -R 755 /var/www/html/public/build
fi

echo "Deployment completed successfully!"