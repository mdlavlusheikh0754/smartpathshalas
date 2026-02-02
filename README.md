# 🎓 Smart Pathshala - School Management System

A comprehensive, production-ready school management system built with Laravel 11, featuring multi-tenancy, Bengali language support, and ZKTime.Net biometric device integration.

## 🌟 **Features**

### ✅ **Core Functionality**
- **Multi-tenant Architecture**: Support for multiple schools
- **Student Management**: Complete student lifecycle management
- **Fee Collection**: Admission and monthly fee collection with Bengali support
- **Inventory Management**: School inventory with class-specific book management
- **Notice Management**: Create, edit, and manage school notices
- **Teacher Management**: Complete teacher profiles and management
- **Exam & Results**: Comprehensive examination and result management
- **Attendance System**: Manual and biometric attendance tracking

### ✅ **Advanced Features**
- **ZKTime.Net Integration**: Biometric device sync for attendance
- **Bengali Language Support**: Full Bengali UI with number conversion
- **Photo Management**: Student and teacher photo upload/display
- **Multi-class Support**: Handle multiple classes and sections
- **Responsive Design**: Mobile-friendly interface
- **API Support**: RESTful APIs for mobile app integration

### ✅ **Technical Features**
- **Laravel 11**: Latest Laravel framework
- **Multi-tenancy**: Isolated tenant databases
- **MySQL Database**: Robust database design
- **File Storage**: Secure file upload and management
- **Caching**: Optimized performance with caching
- **Security**: Production-ready security measures

## 🚀 **Production Deployment**

### **Quick Deploy to VPS**
```bash
# Clone repository
git clone https://github.com/mdlavlusheikh0754/smartpathshala.git
cd smartpathshala

# Run deployment script
./deploy.sh
```

### **Manual Deployment**
See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for detailed instructions.

## 📋 **Requirements**

### **Server Requirements**
- **PHP**: 8.1 or higher
- **MySQL**: 8.0 or higher
- **Nginx/Apache**: Latest stable
- **Composer**: Latest version
- **Node.js**: 18+ (for assets)

### **PHP Extensions**
- BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD, Curl, Zip, Intl

## 🔧 **Installation**

### **1. Clone Repository**
```bash
git clone https://github.com/mdlavlusheikh0754/smartpathshala.git
cd smartpathshala
```

### **2. Install Dependencies**
```bash
composer install
npm install && npm run build
```

### **3. Environment Setup**
```bash
cp .env.production .env
php artisan key:generate
```

### **4. Database Setup**
```bash
# Configure database in .env file
php artisan migrate
php artisan tenants:migrate
```

### **5. Optimize for Production**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🎯 **Usage**

### **Access URLs**
- **Main Application**: `https://yourdomain.com`
- **Admin Dashboard**: `https://yourdomain.com/admin`
- **Tenant Dashboard**: `https://tenant.yourdomain.com`

### **Default Credentials**
- **Email**: `admin@smartpathshala.com`
- **Password**: `password`

## 🔌 **ZKTime.Net Integration**

### **Biometric Device Setup**
1. Install ZKTime.Net 3.3 software
2. Configure device IP (default: 192.168.1.201)
3. Run bridge script: `php zktime_bidirectional_bridge.php`
4. Set up automated sync: `zktime_sync_scheduler.bat`

See [DEVICE_SETUP_GUIDE.md](DEVICE_SETUP_GUIDE.md) for detailed instructions.

## 📚 **Documentation**

- [**Deployment Guide**](DEPLOYMENT_GUIDE.md) - Production deployment instructions
- [**Device Setup Guide**](DEVICE_SETUP_GUIDE.md) - ZKTime.Net integration
- [**API Documentation**](API_DOCUMENTATION.md) - REST API reference
- [**Bengali Font System**](BENGALI_FONT_SYSTEM.md) - Bengali language support
- [**Project Status**](PROJECT_STATUS.md) - Current implementation status

## 🛠️ **Development**

### **Local Development**
```bash
# Start development server
php artisan serve

# Watch for asset changes
npm run dev

# Run tests
php artisan test
```

### **Database Seeding**
```bash
# Seed central database
php artisan db:seed

# Seed tenant database
php artisan tenants:seed
```

## 🔐 **Security**

### **Production Security**
- SSL/TLS encryption required
- Environment variables secured
- File permissions properly set
- Security headers configured
- Input validation and sanitization
- CSRF protection enabled
- SQL injection prevention

## 📊 **Performance**

### **Optimization Features**
- Database query optimization
- Caching (config, routes, views)
- Asset compilation and minification
- Image compression
- Lazy loading
- Database indexing

## 🐛 **Troubleshooting**

### **Common Issues**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Database issues
php artisan migrate:fresh --seed
php artisan tenants:migrate:fresh --seed
```

## 🤝 **Contributing**

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 **Author**

**Lavlu Sheikh**
- GitHub: [@mdlavlusheikh0754](https://github.com/mdlavlusheikh0754)
- Repository: [smartpathshala](https://github.com/mdlavlusheikh0754/smartpathshala)

## 🙏 **Acknowledgments**

- Laravel Framework
- Stancl Tenancy Package
- ZKTeco Integration
- Bengali Language Support Community

## 📞 **Support**

For support and questions:
- Create an issue on GitHub
- Check documentation files
- Review troubleshooting section

---

**Smart Pathshala - Empowering Education Through Technology** 🎓✨