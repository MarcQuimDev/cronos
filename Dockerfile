# Production stage
FROM richarvey/nginx-php-fpm:3.1.6

# Install Node.js 20 (Tailwind v4 needs modern Node)
RUN apk add --no-cache nodejs npm

# Copy application files
COPY . /var/www/html

WORKDIR /var/www/html

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install Composer dependencies
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies
RUN npm ci

# Build assets for production
RUN npm run build

# Verify build output
RUN ls -la public/build/ || echo "Warning: build directory not found"

# Clean up to reduce image size
RUN npm cache clean --force && \
    rm -rf node_modules

# Create necessary directories if they don't exist
RUN mkdir -p storage/framework/{sessions,views,cache} \
    bootstrap/cache

# Set proper permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R 775 /var/www/html/storage \
    /var/www/html/bootstrap/cache && \
    chmod -R 755 /var/www/html/public

CMD ["/start.sh"]