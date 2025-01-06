#!/bin/bash
set -e

# Function to log errors
log_error() {
    echo "[ERROR] $1" >&2
}

# Function to log success
log_success() {
    echo "[SUCCESS] $1"
}

# Navigate to the application directory
cd /var/www

# Ensure proper error handling for each step
try_command() {
    local command="$1"
    local error_message="${2:-Command failed}"

    if ! $command; then
        log_error "$error_message"
        exit 1
    else
        log_success "$command completed successfully"
    fi
}

# Run Composer install with verbose output
try_command "composer install --optimize-autoloader --no-dev" "Composer installation failed"

# try_command "composer clear-cache" "Failed to clear composer cache"

# try_command "composer dump-autoload" "Failed to dump autoload"

# Generate application key
try_command "php artisan key:generate" "Failed to generate application key"

# Cache icons
try_command "php artisan icon:cache" "Failed to cache icons"

# Create storage link
try_command "php artisan storage:link" "Failed to create storage link"

# Run migrations and seed database
try_command "php artisan migrate" "Database migration and seeding failed"

# Additional Laravel optimization steps
try_command "php artisan optimize:clear" "Failed to clear optimizations"
try_command "php artisan config:clear" "Failed to clear configuration"
try_command "php artisan cache:clear" "Failed to clear application cache"

# Run additional Shield-related commands if needed
# try_command "php artisan shield:setup --force" "Failed to setup Shield"
# try_command "php artisan shield:install admin" "Failed to install admin Shield"
# try_command "php artisan shield:generate --all --no-interaction" "Failed to generate Shield configurations"

# Start PHP-FPM
log_success "Starting PHP-FPM"
exec php-fpm
