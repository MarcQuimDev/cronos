#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Installing npm dependencies..."
npm ci --prefix /var/www/html

echo "Building assets with Vite..."
npm run build --prefix /var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force