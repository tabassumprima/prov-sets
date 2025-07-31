#!/bin/bash

# Define application directory
APP_DIR="/var/www/laravel-app"

echo "Installing dependencies for Laravel application..."

# Navigate to the application directory
cd "$APP_DIR" || exit

# Install Composer dependencies
echo "Running composer install..."
composer install --no-dev --optimize-autoloader

# Clear and cache configurations, routes, and views
echo "Caching Laravel configurations..."
php artisan optimize:clear

echo "Dependencies installed successfully!"
