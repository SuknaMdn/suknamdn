#!/bin/bash

# Navigate to the application directory
cd /var/www

# Run Composer install
composer install

# Generate application key
php artisan key:generate

# run icon
php artisan icon:cache

# storage link
php artisan storage:link

# Run migrations and seed
php artisan migrate:fresh --seed

# Start PHP-FPM
php-fpm
