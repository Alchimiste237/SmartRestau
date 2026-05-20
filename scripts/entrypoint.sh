#!/bin/sh
set -e

# Set default DB_DATABASE if not set
export DB_DATABASE="${DB_DATABASE:-/app/storage/db/database.sqlite}"
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"

echo ">>> STARTING ENTRYPOINT SCRIPT <<<"
echo "DB_DATABASE: $DB_DATABASE"

# Ensure the database file exists
mkdir -p "$(dirname "$DB_DATABASE")"
if [ ! -f "$DB_DATABASE" ]; then
    echo "Creating empty database file..."
    touch "$DB_DATABASE"
fi
chmod 666 "$DB_DATABASE"

echo "Running migrations..."
php artisan migrate --force --no-interaction

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ">>> ENTRYPOINT FINISHED <<<"
exec "$@"
