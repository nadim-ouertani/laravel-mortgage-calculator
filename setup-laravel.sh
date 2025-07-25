#!/bin/bash

# Create Laravel 12 project
composer create-project laravel/laravel . "^12.0"

# Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www:www storage bootstrap/cache

# Copy environment configuration
cp .env.example .env

# Generate application key
php artisan key:generate

# Update .env for Docker database connection
sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=tech_exam_mysql/' .env
sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env
sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=mortgage_calculator/' .env
sed -i 's/# DB_USERNAME=root/DB_USERNAME=mortgage_user/' .env
sed -i 's/# DB_PASSWORD=/DB_PASSWORD=secret/' .env

# Add Redis configuration
sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=tech_exam_redis/' .env
sed -i 's/REDIS_PORT=6379/REDIS_PORT=6379/' .env

echo "Laravel 12 setup completed successfully!"