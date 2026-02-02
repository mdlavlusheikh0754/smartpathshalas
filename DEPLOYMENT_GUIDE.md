# 🚀 Smart Pathshala - Production Deployment Guide

## 📋 **Pre-Deployment Checklist**

### ✅ **Server Requirements**
- **PHP**: 8.1 or higher
- **MySQL**: 8.0 or higher
- **Nginx/Apache**: Latest stable version
- **Composer**: Latest version
- **Node.js**: 18+ (for asset compilation)
- **SSL Certificate**: Required for production

### ✅ **VPS Specifications (Recommended)**
- **RAM**: Minimum 2GB (4GB recommended)
- **Storage**: Minimum 20GB SSD
- **CPU**: 2 cores minimum
- **Bandwidth**: Unlimited or high limit

---

## 🔧 **VPS Setup Steps**

### 1. **Server Preparation**
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.1-fpm php8.1-mysql php8.1-xml php8.1-gd php8.1-curl php8.1-mbstring php8.1-zip php8.1-intl php8.1-bcmath

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### 2. **MySQL Database Setup**
```sql
-- Create production database
CREATE DATABASE smartpathshala_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create database user
CREATE USER 'smartpathshala_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON smartpathshala_production.* TO 'smartpathshala_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. **Project Deployment**
```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/Lavlu224/smartpathshala.git
cd smartpathshala

# Set permissions
sudo chown -R www-data:www-data /var/www/smartpathshala
sudo chmod -R 755 /var/www/smartpathshala
sudo chmod -R 775 /var/www/smartpathshala/storage
sudo chmod -R 775 /var/www/smartpathshala/bootstrap/cache

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Environment setup
cp .env.production .env
php artisan key:generate

# Database setup
php artisan migrate --force
php artisan tenants:migrate --force

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. **Nginx Configuration**
```nginx
# /etc/nginx/sites-available/smartpathshala
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/smartpathshala/public;

    # SSL Configuration
    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    index index.php;

    charset utf-8;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Security
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Asset caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # File upload limit
    client_max_body_size 100M;
}
```

### 5. **Enable Site**
```bash
sudo ln -s /etc/nginx/sites-available/smartpathshala /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🔐 **Security Configuration**

### 1. **Firewall Setup**
```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### 2. **SSL Certificate (Let's Encrypt)**
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 3. **Environment Security**
```bash
# Secure .env file
sudo chmod 600 /var/www/smartpathshala/.env
sudo chown www-data:www-data /var/www/smartpathshala/.env
```

---

## 📊 **Monitoring & Maintenance**

### 1. **Log Monitoring**
```bash
# Laravel logs
tail -f /var/www/smartpathshala/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log
```

### 2. **Backup Script**
```bash
#!/bin/bash
# /home/backup/smartpathshala_backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/backup"
PROJECT_DIR="/var/www/smartpathshala"

# Database backup
mysqldump -u smartpathshala_user -p smartpathshala_production > $BACKUP_DIR/db_backup_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz $PROJECT_DIR

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

### 3. **Cron Jobs**
```bash
# Add to crontab (crontab -e)
# Laravel scheduler
* * * * * cd /var/www/smartpathshala && php artisan schedule:run >> /dev/null 2>&1

# Daily backup
0 2 * * * /home/backup/smartpathshala_backup.sh

# ZKTime.Net sync (if using)
*/2 * * * * cd /var/www/smartpathshala && php zktime_bidirectional_bridge.php >> /var/log/zktime_sync.log 2>&1
```

---

## 🎯 **Post-Deployment Testing**

### ✅ **Functionality Tests**
1. **Access main site**: `https://yourdomain.com`
2. **Test student management**: Add/edit students
3. **Test fee collection**: Both admission and monthly
4. **Test inventory system**: Add items with class selection
5. **Test notice system**: Create/edit/delete notices
6. **Test ZKTime.Net integration**: Device sync functionality

### ✅ **Performance Tests**
```bash
# Test site speed
curl -o /dev/null -s -w "%{time_total}\n" https://yourdomain.com

# Check memory usage
free -h

# Check disk usage
df -h
```

---

## 🚨 **Troubleshooting**

### **Common Issues**

#### **Permission Errors**
```bash
sudo chown -R www-data:www-data /var/www/smartpathshala
sudo chmod -R 755 /var/www/smartpathshala
sudo chmod -R 775 /var/www/smartpathshala/storage
sudo chmod -R 775 /var/www/smartpathshala/bootstrap/cache
```

#### **Database Connection Issues**
```bash
# Test database connection
php artisan tinker
# In tinker: DB::connection()->getPdo();
```

#### **Cache Issues**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

#### **File Upload Issues**
```bash
# Check PHP configuration
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Update if needed in /etc/php/8.1/fpm/php.ini
upload_max_filesize = 100M
post_max_size = 100M
```

---

## 🎉 **Production Checklist**

### ✅ **Before Going Live**
- [ ] SSL certificate installed and working
- [ ] Database properly configured and migrated
- [ ] All environment variables set correctly
- [ ] File permissions configured
- [ ] Nginx/Apache configured and tested
- [ ] Firewall configured
- [ ] Backup system in place
- [ ] Monitoring set up
- [ ] Performance optimized (caching enabled)
- [ ] Security headers configured
- [ ] Error pages customized
- [ ] Domain DNS configured
- [ ] Email configuration tested
- [ ] ZKTime.Net integration tested (if applicable)

### ✅ **Post-Launch**
- [ ] Monitor logs for errors
- [ ] Test all functionality
- [ ] Set up regular backups
- [ ] Monitor performance
- [ ] Set up uptime monitoring
- [ ] Document admin procedures
- [ ] Train users on new system

---

## 📞 **Support & Maintenance**

### **Regular Maintenance Tasks**
- Weekly: Check logs and performance
- Monthly: Update dependencies and security patches
- Quarterly: Full system backup and disaster recovery test

### **Emergency Contacts**
- Server Provider Support
- Domain Registrar Support
- SSL Certificate Provider

**Your Smart Pathshala system is now ready for production deployment!** 🚀