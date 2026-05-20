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
    libxml2-dev \
    sqlite-dev

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
    CMD wget --quiet --tries=1 --spider http://localhost:${PORT:-80}/ || exit 1

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Copy built assets from Stage 1
COPY --from=assets-builder /app/public/build ./public/build

# Fix line endings and permissions for entrypoint
RUN sed -i 's/\r$//' /app/scripts/entrypoint.sh && chmod +x /app/scripts/entrypoint.sh

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions for Laravel
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Environment variables for production
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV SESSION_DRIVER=file
ENV CACHE_DRIVER=file
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/app/storage/db/database.sqlite

ENTRYPOINT ["/bin/sh", "/app/scripts/entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
