# Production stage
FROM richarvey/nginx-php-fpm:3.1.6

# Install Node.js for building assets
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

# Install Node dependencies and build assets
RUN npm ci && npm run build && npm cache clean --force

# Remove node_modules to reduce image size
RUN rm -rf node_modules

# Set proper permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["/start.sh"]