#!/bin/sh
set -e

# Set default DB_DATABASE if not set
export DB_DATABASE="${DB_DATABASE:-/app/storage/db/database.sqlite}"

echo "Starting entrypoint script..."
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_DATABASE: $DB_DATABASE"

# Ensure the database directory exists and has correct permissions
echo "Ensuring directory $(dirname "$DB_DATABASE") exists..."
mkdir -p $(dirname "$DB_DATABASE")
chown -R www-data:www-data $(dirname "$DB_DATABASE")

# Ensure the database file exists if using SQLite
if [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "SQLite connection detected. Checking if $DB_DATABASE exists..."
    if [ ! -f "$DB_DATABASE" ]; then
        echo "Creating $DB_DATABASE..."
        touch "$DB_DATABASE"
    fi
    echo "Setting permissions on $DB_DATABASE..."
    chown www-data:www-data "$DB_DATABASE"
    chmod 664 "$DB_DATABASE"
fi

echo "Running migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Entrypoint script finished. Executing: $@"

# Execute the main command
exec "$@"
