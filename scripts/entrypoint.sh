#!/bin/sh
set -e

# Ensure the database directory exists and has correct permissions
mkdir -p $(dirname "$DB_DATABASE")
chown -R www-data:www-data $(dirname "$DB_DATABASE")

# Ensure the database file exists if using SQLite
if [ "$DB_CONNECTION" = "sqlite" ]; then
    if [ ! -f "$DB_DATABASE" ]; then
        touch "$DB_DATABASE"
        chown www-data:www-data "$DB_DATABASE"
    fi
fi

# Run migrations
php artisan migrate --force

# Cache configuration and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Execute the main command
exec "$@"
