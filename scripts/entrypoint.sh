#!/bin/sh
set -e

# Set default DB_DATABASE if not set
export DB_DATABASE="${DB_DATABASE:-/app/storage/db/database.sqlite}"
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"

echo "Starting entrypoint script..."
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_DATABASE: $DB_DATABASE"

# Ensure the directory exists
DB_DIR=$(dirname "$DB_DATABASE")
if [ ! -d "$DB_DIR" ]; then
    echo "Creating directory $DB_DIR..."
    mkdir -p "$DB_DIR"
fi

# Ensure the database file exists if using SQLite
if [ "$DB_CONNECTION" = "sqlite" ]; then
    if [ ! -f "$DB_DATABASE" ]; then
        echo "Creating database file $DB_DATABASE..."
        touch "$DB_DATABASE"
    fi
    echo "Setting permissions..."
    chmod 666 "$DB_DATABASE"
    chmod 777 "$DB_DIR"
fi

# Optimize Laravel
echo "Running migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Ready to serve!"
exec "$@"
