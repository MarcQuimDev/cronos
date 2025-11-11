#!/usr/bin/env bash
# More tolerant deployment script - don't exit on first error
set +e  # Don't exit on error, but track failures

echo "=========================================="
echo "Starting Laravel deployment..."
echo "=========================================="

# Track if any critical errors occur
CRITICAL_ERROR=0

echo ""
echo "[1/9] Running composer install..."
if composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --working-dir=/var/www/html; then
    echo "✓ Composer install successful"
else
    echo "✗ Composer install failed!"
    CRITICAL_ERROR=1
fi

echo ""
echo "[2/9] Generating application key if not set..."
php artisan key:generate --force || echo "⚠ Key already exists or generation failed (non-critical)"

echo ""
echo "[3/9] Clearing caches..."
php artisan cache:clear || echo "⚠ Cache clear failed (non-critical)"
php artisan config:clear || echo "⚠ Config clear failed (non-critical)"
php artisan route:clear || echo "⚠ Route clear failed (non-critical)"
php artisan view:clear || echo "⚠ View clear failed (non-critical)"

echo ""
echo "[4/9] Caching config..."
if php artisan config:cache; then
    echo "✓ Config cached"
else
    echo "⚠ Config cache failed (will continue)"
fi

echo ""
echo "[5/9] Caching routes..."
php artisan route:cache || echo "⚠ Route cache failed (non-critical)"

echo ""
echo "[6/9] Caching views..."
php artisan view:cache || echo "⚠ View cache failed (non-critical)"

echo ""
echo "[7/9] Running migrations..."
if php artisan migrate --force; then
    echo "✓ Migrations completed"
else
    echo "⚠ Migrations failed (database may not be configured)"
fi

echo ""
echo "[8/9] Verifying build assets..."
if [ -f "/var/www/html/public/build/manifest.json" ]; then
    echo "✓ Vite manifest.json found"
    ls -la /var/www/html/public/build/
else
    echo "✗ ERROR: Vite manifest.json NOT FOUND!"
    CRITICAL_ERROR=1
fi

echo ""
echo "[9/9] Setting permissions..."
chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache || echo "⚠ Permission change failed"
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || echo "⚠ Chmod failed"

if [ -d "/var/www/html/public/build" ]; then
    chown -R nginx:nginx /var/www/html/public/build || echo "⚠ Build dir permission change failed"
    chmod -R 755 /var/www/html/public/build || echo "⚠ Build dir chmod failed"
fi

echo ""
echo "=========================================="
if [ $CRITICAL_ERROR -eq 0 ]; then
    echo "✓ Deployment completed successfully!"
else
    echo "⚠ Deployment completed with errors (check logs above)"
fi
echo "=========================================="

# Always exit 0 so container starts even if some non-critical tasks failed
exit 0