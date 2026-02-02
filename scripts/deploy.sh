#!/bin/bash

# Smart Pathshala Production Deployment Script
# Usage: ./deploy.sh

echo "🚀 Smart Pathshala Production Deployment"
echo "========================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

print_status "Starting deployment process..."

# 1. Pull latest changes from Git
print_status "Pulling latest changes from Git..."
git pull origin main

# 2. Install/Update Composer dependencies
print_status "Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# 3. Install/Update NPM dependencies and build assets
print_status "Installing NPM dependencies and building assets..."
npm install
npm run build

# 4. Run database migrations
print_status "Running database migrations..."
php artisan migrate --force

# 5. Run tenant migrations
print_status "Running tenant migrations..."
php artisan tenants:migrate --force

# 6. Clear and cache configuration
print_status "Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set proper permissions
print_status "Setting file permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 8. Restart services
print_status "Restarting services..."
sudo systemctl reload nginx
sudo systemctl restart php8.1-fpm

# 9. Test application
print_status "Testing application..."
if curl -f -s http://localhost > /dev/null; then
    print_status "Application is responding correctly"
else
    print_warning "Application may not be responding correctly"
fi

print_status "Deployment completed successfully! 🎉"
print_warning "Don't forget to:"
echo "  - Test all functionality"
echo "  - Check error logs"
echo "  - Monitor performance"
echo "  - Backup database"

echo ""
echo "📊 Quick Status Check:"
echo "  - Laravel Version: $(php artisan --version)"
echo "  - PHP Version: $(php -v | head -n1)"
echo "  - Database Status: $(php artisan migrate:status | tail -n1)"
echo ""
print_status "Smart Pathshala is ready for production! 🚀"