# Stage 1: Build front-end assets
FROM node:20-alpine AS assets-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Final image
FROM dunglas/frankenphp:1.4-php8.3-alpine

# Install system dependencies
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    icu-dev \
    oniguruma-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        intl \
        pcntl \
        bcmath \
        zip \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        exif \
        opcache

# Set up PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Add healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
    CMD wget --quiet --tries=1 --spider http://localhost/ || exit 1

# Set working directory
WORKDIR /app

# Copy entrypoint script first and set permissions
COPY scripts/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Create a dedicated directory for the SQLite database to avoid volume conflicts
RUN mkdir -p /app/storage/db && chown www-data:www-data /app/storage/db

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Copy built assets from Stage 1
COPY --from=assets-builder /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions for Laravel
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Environment variables for production
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV FRANKENPHP_CONFIG="import /app/Caddyfile"

ENTRYPOINT ["entrypoint.sh"]
CMD ["frankenphp", "run-config", "/app/Caddyfile"]
