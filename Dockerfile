# Build stage for Node.js assets
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy source files needed for build
COPY resources ./resources
COPY vite.config.js ./
COPY tailwind.config.js* ./
COPY postcss.config.js* ./

# Build assets
RUN npm run build

# Production stage
FROM richarvey/nginx-php-fpm:3.1.6

# Copy application files
COPY . /var/www/html

# Copy built assets from node-builder stage
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

# Set proper permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["/start.sh"]