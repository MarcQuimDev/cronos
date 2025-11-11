# Build stage for assets
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install Node dependencies
RUN npm ci

# Copy source files for building
COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js tailwind.config.js ./

# Build assets
RUN npm run build && \
    ls -la public/build/ || echo "Build directory not found"

# Production stage
FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Copy built assets from node-builder
COPY --from=node-builder /app/public/build /var/www/html/public/build

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

# Create necessary directories if they don't exist
RUN mkdir -p storage/framework/{sessions,views,cache} \
    bootstrap/cache

# Set proper permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R 775 /var/www/html/storage \
    /var/www/html/bootstrap/cache && \
    chmod -R 755 /var/www/html/public

CMD ["/start.sh"]