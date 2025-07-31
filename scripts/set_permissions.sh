#!/bin/bash

# Define application directory
APP_DIR="/var/www/laravel-app"

echo "Setting ownership and permissions for Laravel application..."

# Set ownership to the current user and group to www-data
chown -R $USER:www-data "$APP_DIR"

# Set file permissions to 644 and directory permissions to 755
find "$APP_DIR" -type f -exec chmod 644 {} \;
find "$APP_DIR" -type d -exec chmod 755 {} \;

# Set group ownership and permissions for storage and cache directories
chgrp -R www-data "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
chmod -R ug+rwx "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

# Navigate to the application directory
cd "$APP_DIR" || exit

php artisan migrate --force

php artisan optimize:clear

echo "Permissions and ownership set successfully!"
