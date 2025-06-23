# 🚀 PANDUAN INSTALASI LENGKAP MEDTRACK

## 📋 Prerequisites

### Sistem Requirements
- **Operating System**: Windows 10/11, macOS, atau Linux
- **PHP**: 8.1 atau lebih tinggi
- **MySQL**: 8.0 atau MariaDB 10.3+
- **Composer**: Latest version
- **Node.js**: 16.x atau lebih tinggi
- **NPM**: 8.x atau lebih tinggi

### Tools yang Diperlukan
- **XAMPP/LARAGON/WAMP**: Untuk development environment
- **Git**: Version control
- **Code Editor**: VS Code, PHPStorm, atau similar
- **Browser**: Chrome, Firefox, Edge (untuk testing)

## 🏁 Quick Start (5 Menit)

> **📌 CATATAN PENTING**: Mulai versi terbaru, konfigurasi WhatsApp API tidak lagi melalui file `.env` tetapi melalui web interface (menu Pengaturan) setelah sistem berjalan.

### 1. Clone & Install
```bash
git clone <repository-url> medtrack
cd medtrack
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database & Settings Setup
```bash
# Buat database di MySQL/phpMyAdmin
# Nama database: db_MedTrack

# Run migrations dan seeders
php artisan migrate
php artisan db:seed
php artisan db:seed --class=SettingsSeeder
```

**⚠️ WhatsApp API dikonfigurasi via web interface setelah login**

### 5. Start Development Server
```bash
php artisan serve
npm run dev
```

**✅ Sistem siap digunakan di http://localhost:8000**

## 🔧 Instalasi Detail

### A. Development Environment Setup

#### Option 1: LARAGON (Windows - Recommended)
1. Download dan install [Laragon](https://laragon.org/)
2. Start Apache & MySQL
3. Clone project ke folder `C:\laragon\www\`
4. Akses via `http://medtrack.test`

#### Option 2: XAMPP (Cross-platform)
1. Download dan install [XAMPP](https://www.apachefriends.org/)
2. Start Apache & MySQL
3. Clone project ke folder `htdocs/`
4. Akses via `http://localhost/medtrack/public`

#### Option 3: Docker (Advanced)
```bash
# Coming soon - Docker setup
```

### B. Database Configuration

#### MySQL Setup
```sql
-- Login ke MySQL
mysql -u root -p

-- Buat database
CREATE DATABASE db_MedTrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Buat user khusus (optional)
CREATE USER 'medtrack_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON db_MedTrack.* TO 'medtrack_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Environment Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_MedTrack
DB_USERNAME=root
DB_PASSWORD=
```

### C. WhatsApp API (Fonnte) Setup

#### 1. Daftar Akun Fonnte
1. Kunjungi [https://fonnte.com](https://fonnte.com)
2. Klik "Daftar" dan buat akun baru
3. Verifikasi email

#### 2. Verifikasi WhatsApp
1. Login ke dashboard Fonnte
2. Pilih "WhatsApp" → "Add Device"
3. Scan QR code dengan WhatsApp
4. Tunggu hingga status "Connected"

#### 3. Dapatkan API Key
1. Dashboard → "API" → "Get API Key"
2. Copy API key yang diberikan
3. **JANGAN MASUKKAN KE .env LAGI!**

#### 4. Konfigurasi via Web Interface
Setelah sistem Laravel berjalan:
1. Login sebagai admin ke sistem
2. Buka menu **"Pengaturan"**  
3. Konfigurasi WhatsApp API:
   - **API Key**: Paste API key dari Fonnte
   - **Sender Number**: Format `628xxxxxxxxxx`
   - **API URL**: Biarkan default
4. Klik **"Simpan Pengaturan"**

#### 5. Test API
```bash
php artisan test:pengingat-obat --dry-run
```

### D. Production Deployment

#### 1. Shared Hosting (cPanel)
```bash
# Upload files via File Manager atau FTP
# Extract ke public_html/

# Set environment
cp .env.example .env
# Edit .env sesuai hosting

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Cache for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 2. VPS/Dedicated Server
```bash
# Install requirements
sudo apt update
sudo apt install php8.1 php8.1-fpm php8.1-mysql nginx mysql-server

# Setup domain
sudo nano /etc/nginx/sites-available/medtrack.com

# SSL Certificate (Let's Encrypt)
sudo certbot --nginx -d medtrack.com

# Setup cron job
crontab -e
# Add: * * * * * cd /var/www/medtrack && php artisan schedule:run >> /dev/null 2>&1
```

#### 3. Cloud Hosting (AWS, DigitalOcean, etc.)
```bash
# Similar to VPS setup
# Use load balancer for high availability
# Setup database cluster for scaling
```

## ⚡ Performance Optimization

### 1. PHP Optimization
```ini
; php.ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 20M
post_max_size = 20M
```

### 2. Database Optimization
```sql
-- Add indexes for better performance
ALTER TABLE jadwal ADD INDEX idx_tanggal_status (tanggal_waktu_pengingat, status);
ALTER TABLE pasien ADD INDEX idx_telepon (nomor_telepon);
```

### 3. Laravel Optimization
```bash
# Production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear all cache
php artisan optimize:clear
```

## 🧪 Testing Setup

### 1. Unit Testing
```bash
# Install testing dependencies
composer install --dev

# Run tests
php artisan test

# Run specific test
php artisan test --filter=PengingatObatTest
```

### 2. Feature Testing
```bash
# Test WhatsApp integration
php artisan test:pengingat-obat --dry-run

# Test URL generation
php artisan test:url-generation

# Test database connection
php artisan migrate:status
```

### 3. Load Testing
```bash
# Install Apache Bench
sudo apt install apache2-utils

# Test performance
ab -n 1000 -c 10 http://localhost:8000/
```

## 🔒 Security Setup

### 1. Environment Security
```env
# Strong APP_KEY
APP_KEY=base64:RANDOM_64_CHARACTER_STRING

# Secure database password
DB_PASSWORD=very_secure_random_password

# Hide sensitive info
APP_DEBUG=false
APP_ENV=production
```

### 2. File Permissions
```bash
# Set proper permissions
chmod -R 755 /var/www/medtrack
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data /var/www/medtrack
```

### 3. Firewall & Security
```bash
# Enable firewall
sudo ufw enable
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443

# Install fail2ban
sudo apt install fail2ban
```

## 📊 Monitoring & Logging

### 1. Log Configuration
```env
# Log channels
LOG_CHANNEL=daily
LOG_LEVEL=info
LOG_DAYS=14
```

### 2. Monitoring Tools
```bash
# Install monitoring tools
sudo apt install htop iotop

# Check system resources
htop
df -h
free -m
```

### 3. Application Monitoring
```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Monitor WhatsApp job logs
grep "WhatsApp" storage/logs/laravel.log

# Monitor database queries
tail -f /var/log/mysql/mysql.log
```

## 🚨 Troubleshooting Lengkap

### Common Issues

#### 1. Composer Install Error
```bash
# Update composer
composer self-update

# Clear cache
composer clear-cache

# Install with memory limit
php -d memory_limit=-1 composer install
```

#### 2. NPM Install Error
```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules and reinstall
rm -rf node_modules package-lock.json
npm install
```

#### 3. Permission Denied
```bash
# Fix Laravel permissions
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 4. Database Connection Failed
```bash
# Check MySQL status
sudo systemctl status mysql

# Restart MySQL
sudo systemctl restart mysql

# Check connection
mysql -u root -p -e "SELECT 1"
```

#### 5. WhatsApp API Error
1. **Invalid API Key**: 
   - Buka menu **Pengaturan** di sistem
   - Verifikasi API key sesuai dengan dashboard Fonnte
2. **Device Disconnected**: 
   - Reconnect WhatsApp di dashboard Fonnte
   - Test ulang via menu Pengaturan
3. **Insufficient Balance**: 
   - Top up saldo di dashboard Fonnte
4. **Invalid Number Format**: 
   - Edit di menu **Pengaturan** 
   - Gunakan format `628xxxxxxxxxx`

### Advanced Debugging

#### 1. Debug Mode
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

#### 2. Query Debugging
```php
// Add to AppServiceProvider
DB::listen(function ($query) {
    Log::info($query->sql, $query->bindings);
});
```

#### 3. Performance Profiling
```bash
# Install Laravel Debugbar
composer require barryvdh/laravel-debugbar --dev

# Install Telescope
composer require laravel/telescope --dev
php artisan telescope:install
```

## 📱 Mobile Testing

### 1. Responsive Testing
- Chrome DevTools (F12)
- Firefox Responsive Design Mode
- Safari Web Inspector

### 2. Real Device Testing
- Test link konfirmasi di berbagai device
- Verifikasi WhatsApp integration
- Check loading speed

### 3. Browser Compatibility
- Chrome (recommended)
- Firefox
- Safari
- Edge
- Mobile browsers

## 🎯 Next Steps

Setelah instalasi berhasil:

1. **✅ Setup Settings**: Konfigurasi WhatsApp API via menu Pengaturan
2. **✅ Setup Users**: Buat akun admin pertama
3. **✅ Add Patients**: Input data pasien
4. **✅ Create Schedules**: Buat jadwal pengingat obat
5. **✅ Test WhatsApp**: Kirim test message via menu Pengaturan
6. **✅ Setup Cron**: Aktifkan pengiriman otomatis
7. **✅ Monitor**: Pantau logs dan performance

## 🔧 Sistem Pengaturan Dinamis

### Fitur Pengaturan Terbaru
- **Database-based**: Semua pengaturan disimpan di database
- **Web Interface**: Kelola via admin panel, tidak perlu edit file
- **Real-time**: Perubahan langsung aktif tanpa restart
- **Security**: Sensitive data terenkripsi otomatis
- **Kategorisasi**: Pengaturan terorganisir per grup

### Migrasi dari .env ke Database
Jika sebelumnya menggunakan .env untuk WhatsApp API:

```bash
# Backup .env lama
cp .env .env.backup

# Hapus konfigurasi WhatsApp dari .env
# Tidak perlu lagi:
# WHATSAPP_API_KEY=
# WHATSAPP_SENDER_NUMBER=
# WHATSAPP_API_URL=

# Jalankan seeder untuk setup pengaturan
php artisan db:seed --class=SettingsSeeder
```

### Penggunaan Menu Pengaturan
1. Login sebagai admin
2. Klik menu **"Pengaturan"**
3. Pilih kategori pengaturan (WhatsApp, General, dll)
4. Edit nilai pengaturan
5. Klik **"Simpan"**
6. Pengaturan langsung aktif

**🎉 Selamat! Sistem MedTrack siap digunakan!**

## 📚 Dokumentasi Tambahan

- `MIGRASI_ENV_TO_DATABASE.md` - Panduan migrasi settings
- `SISTEM_PENGATURAN_DINAMIS.md` - Detail sistem pengaturan
- `VIEW_BLADE_COMPONENTS_FIX.md` - Perbaikan view components

**🎉 Selamat! Sistem MedTrack siap digunakan!**
