#!/bin/bash

# Script de deploy simplificado

echo "🚀 Starting deployment..."

# Pull latest changes
echo "📥 Pulling latest code..."
git pull origin main

# Install/update dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# Clear and cache config
echo "🔄 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "📝 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
echo "⚡ Optimizing autoloader..."
composer dump-autoload --optimize

# Restart queue workers
echo "🔄 Restarting queue workers..."
php artisan queue:restart

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment completed successfully!"
