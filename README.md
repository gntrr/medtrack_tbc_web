# 💊 MedTrack - Sistem Penjadwalan dan Pengingat Obat

<p align="center">
  <strong>Sistem manajemen jadwal pengobatan dan pengingat minum obat otomatis via WhatsApp</strong>
</p>

<p align="center">
<img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
<img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
<img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
<img src="https://img.shields.io/badge/WhatsApp_API-25D366?style=for-the-badge&logo=whatsapp&logoColor=white" alt="WhatsApp API">
</p>

## 📋 Tentang MedTrack

**MedTrack** adalah sistem penjadwalan dan pengingat obat yang dirancang khusus untuk membantu tenaga kesehatan mengelola jadwal pengobatan pasien dan memastikan kepatuhan minum obat melalui pengingat otomatis via WhatsApp.

### 🎯 Fitur Utama

#### 🏥 **Untuk Tenaga Kesehatan**
- **Manajemen Pasien**: Pendaftaran dan pengelolaan data pasien
- **Penjadwalan Fleksibel**: Buat jadwal kontrol dan pengingat obat harian
- **Bulk Operations**: Buat pengingat massal untuk multiple pasien
- **Monitoring Kepatuhan**: Dashboard statistik dan laporan kepatuhan
- **Manajemen Jadwal**: Reschedule, edit, dan hapus jadwal

#### 👤 **Untuk Pasien**
- **Pengingat WhatsApp**: Notifikasi otomatis waktu minum obat
- **Konfirmasi Online**: Link konfirmasi langsung di pesan WhatsApp
- **Tracking Kepatuhan**: History konfirmasi dan status pengobatan
- **Interface Mudah**: Form konfirmasi yang user-friendly

#### 🤖 **Sistem Otomatis**
- **Pengiriman Terjadwal**: Cron job otomatis untuk pengingat harian
- **WhatsApp Integration**: API Fonnte untuk pengiriman pesan
- **Token Security**: Link konfirmasi dengan token unik dan expired
- **Auto Cleanup**: Pembersihan data lama secara otomatis
- **Audit Logging**: Log semua aktivitas untuk monitoring

#### ⚙️ **Sistem Pengaturan Dinamis**
- **Database Settings**: Konfigurasi tersimpan di database, tidak di .env
- **Web Interface**: Kelola pengaturan via admin panel
- **Real-time Update**: Perubahan langsung aktif tanpa restart server
- **Security**: Sensitive data terenkripsi otomatis
- **Multi-group**: Pengaturan terorganisir per kategori

## 🏗️ Arsitektur Sistem

### 📊 **Database Schema**
```
├── users (tenaga kesehatan)
├── pasien (data pasien)
├── jadwal (schedules: kontrol & obat)
├── riwayat (activity history)
└── settings (dynamic configuration)
```

### 🔄 **Flow Pengingat Obat**
```
1. Admin buat jadwal pengingat obat
2. Cron job kirim WhatsApp sesuai jadwal
3. Pasien terima pesan dengan link konfirmasi
4. Pasien klik link → Form konfirmasi
5. Sistem catat konfirmasi → Update status
6. Admin monitor kepatuhan di dashboard
```

### 🌐 **Domain Hosting**
Sistem otomatis menyesuaikan link konfirmasi dengan domain hosting:
- **Development**: `http://localhost:8000/konfirmasi-obat/{token}`
- **Production**: `https://yourdomain.com/konfirmasi-obat/{token}`
## 🚀 Instalasi dan Konfigurasi

### 📋 Requirements
- **PHP**: 8.1 atau lebih tinggi
- **Composer**: Package manager PHP
- **Node.js & NPM**: Untuk asset compilation
- **MySQL**: 8.0 atau lebih tinggi
- **Web Server**: Apache/Nginx

### 🔧 Langkah Instalasi

#### 1. Clone Repository
```bash
git clone <repository-url>
cd penjadwalan_obat
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

#### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE db_MedTrack;
exit

# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

#### 5. Setup WhatsApp API Settings
```bash
# Run seeders to setup initial settings
php artisan db:seed --class=SettingsSeeder
```

**⚠️ PENTING: Konfigurasi WhatsApp API dilakukan melalui web interface**

Setelah sistem berjalan:
1. Login sebagai admin
2. Buka menu **"Pengaturan"**
3. Konfigurasi WhatsApp API:
   - **API Key**: Masukkan API key dari Fonnte
   - **Sender Number**: Format `628xxxxxxxxxx`
   - **API URL**: Biarkan default `https://api.fonnte.com/send`

#### 6. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

#### 7. Set Permissions (Linux/Mac)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 8. Web Server Configuration

**Apache (.htaccess)**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Nginx**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/project/public;
    
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## ⚙️ Konfigurasi Production

### 🌐 Domain Setup
Update file `.env` untuk production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database production
DB_HOST=your_db_host
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 🔄 Cron Job Setup
**PENTING:** Sistem ini menjalankan 2 jenis pengingat (kontrol + obat) melalui 1 cron job:

```bash
# Edit crontab
crontab -e

# Tambahkan HANYA baris ini (Laravel scheduler akan handle semua):
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled Tasks yang Berjalan Otomatis:**
- ⏰ `app:send-reminders` - Cek pengingat kontrol & obat setiap menit
- 🧹 `pengingat:bersihkan-obat` - Cleanup data lama setiap hari jam 1 pagi

**📊 Monitor Cron Job:**
```bash
# Test manual
php artisan schedule:run

# Monitor log
tail -f storage/logs/pengingat.log
```

### 🗂️ Cache Optimization
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## 📱 Penggunaan Sistem

### 👨‍⚕️ **Untuk Admin/Tenaga Kesehatan**

#### Login ke Sistem
1. Buka `https://yourdomain.com`
2. Login dengan akun admin
3. Dashboard utama akan menampilkan statistik

#### Konfigurasi WhatsApp API (Pertama Kali)
1. **Menu Pengaturan** → **WhatsApp API Settings**
2. Masukkan **API Key** dari dashboard Fonnte
3. Masukkan **Nomor WhatsApp** pengirim (format: 628xxxxxxxxxx)
4. Klik **"Simpan Pengaturan"**
5. Test pengiriman pesan untuk memastikan konfigurasi benar

#### Manajemen Pasien
1. **Menu Pasien** → **Tambah Pasien Baru**
2. Isi data: nama, nomor telepon, alamat
3. Simpan data pasien

#### Buat Pengingat Obat
1. **Menu Pengingat Obat** → **Buat Pengingat**
2. Pilih pasien dan waktu pengingat
3. Tentukan jadwal harian (hari apa saja)
4. Simpan pengingat

#### Bulk Create (Massal)
1. **Menu Pengingat Obat** → **Buat Massal**
2. Pilih multiple pasien
3. Set waktu pengingat untuk semua
4. Sistem akan buat pengingat untuk semua pasien terpilih

#### Monitor Kepatuhan
1. **Menu Pengingat Obat** → **Statistik**
2. Lihat grafik kepatuhan harian/bulanan
3. Filter berdasarkan pasien atau periode

### 👤 **Untuk Pasien**

#### Menerima Pengingat
1. Pasien akan menerima WhatsApp pada waktu yang dijadwalkan
2. Pesan berisi pengingat dan link konfirmasi
3. Contoh pesan:
   ```
   Halo John Doe, ini pengingat untuk minum obat TBC hari ini pukul 08:00.
   
   Silakan klik link berikut untuk mengonfirmasi bahwa Anda sudah minum obat:
   https://yourdomain.com/konfirmasi-obat/abc123def456
   
   Terima kasih, konfirmasi Anda telah diterima.
   ```

#### Konfirmasi Minum Obat
1. Klik link di pesan WhatsApp
2. Halaman konfirmasi akan terbuka di browser
3. Isi form konfirmasi (waktu minum, catatan)
4. Klik "Konfirmasi"
5. Status akan tersimpan di sistem

## 🔧 Command Line Tools

### 📤 Pengiriman Manual
```bash
# Kirim pengingat obat hari ini
php artisan kirim:pengingat-obat

# Kirim dengan dry-run (test mode)
php artisan kirim:pengingat-obat --dry-run

# Kirim untuk tanggal specific
php artisan kirim:pengingat-obat --tanggal=2025-06-23
```

### 🧹 Maintenance
```bash
# Bersihkan data jadwal lama (>30 hari)
php artisan bersihkan:jadwal-obat

# Bersihkan paksa (tanpa konfirmasi)
php artisan bersihkan:jadwal-obat --force
```

### 🧪 Testing
```bash
# Test pengiriman pengingat
php artisan test:pengingat-obat

# Test URL generation
php artisan test:url-generation
```

### 📊 Sample Data
```bash
# Generate sample data untuk testing
php artisan db:seed --class=PengingatObatSeeder
```

## 🔐 Keamanan

### 🛡️ Token Konfirmasi
- **Format**: 32 karakter alphanumeric
- **Unique**: Setiap jadwal memiliki token unik
- **Expiration**: Valid selama 12 jam setelah pengiriman
- **Single Use**: Token hanya bisa digunakan sekali

### 🔒 Validasi
- ✅ Token validation pada setiap request
- ✅ Time-based expiration check
- ✅ Status validation (sudah dikonfirmasi atau belum)
- ✅ Rate limiting untuk mencegah abuse

### 📝 Audit Logging
Semua aktivitas tercatat di log:
- Pengiriman pengingat WhatsApp
- Akses link konfirmasi
- Konfirmasi pasien
- Error dan exception

## 🐛 Troubleshooting

### ❌ WhatsApp Tidak Terkirim
1. **Cek Pengaturan API**: Menu Pengaturan → Verifikasi API Key
2. **Cek Saldo**: Login ke dashboard Fonnte, cek saldo
3. **Cek Nomor**: Pastikan format nomor `628xxxxxxxxxx` di menu Pengaturan
4. **Test Pengiriman**: Gunakan fitur test di menu Pengaturan
5. **Cek Log**: `tail -f storage/logs/laravel.log`

### ❌ Link Konfirmasi Error
1. **Cek APP_URL**: Pastikan `APP_URL` sesuai domain
2. **Clear Cache**: `php artisan config:clear`
3. **Cek Routes**: `php artisan route:list | grep konfirmasi`

### ❌ Cron Job Tidak Jalan
1. **Cek Crontab**: `crontab -l`
2. **Test Manual**: `php artisan schedule:run`
3. **Cek Permission**: Pastikan user bisa akses project folder

### ❌ Database Connection Error
1. **Cek Kredensial**: Verifikasi `DB_*` di `.env`
2. **Test Connection**: `php artisan migrate:status`
3. **Cek Database**: Pastikan database exists

## 📁 Struktur File Penting

```
app/
├── Console/Commands/
│   ├── KirimPengingatObat.php      # Command pengiriman
│   ├── PembersihJadwalObat.php     # Command cleanup
│   └── TestPengingatObat.php       # Command testing
├── Http/Controllers/
│   ├── PengingatObatController.php # CRUD pengingat obat
│   ├── KonfirmasiObatController.php # Handle konfirmasi
│   └── SettingsController.php      # Pengaturan dinamis
├── Jobs/
│   └── KirimPengingatObatHarian.php # Background job WhatsApp
├── Models/
│   ├── Jadwal.php                  # Model jadwal
│   ├── Pasien.php                  # Model pasien
│   └── Setting.php                 # Model pengaturan dinamis
└── Services/
    └── WhatsAppService.php         # Service WhatsApp API (DB-based)

resources/views/
├── pengingat-obat/                 # Views admin pengingat obat
├── konfirmasi-obat/                # Views konfirmasi pasien
└── settings/                       # Views pengaturan dinamis

database/
├── migrations/                     # Database schema
└── seeders/                        # Sample data + SettingsSeeder
```

## 📞 Support & Kontribusi

### 🆘 Bantuan
Jika mengalami kesulitan dalam instalasi atau konfigurasi:
1. Cek dokumentasi tambahan di folder `docs/` atau file dokumentasi:
   - `INSTALASI_LENGKAP.md` - Panduan instalasi detail
   - `DOMAIN_HOSTING_SETUP.md` untuk setup production
   - `FORMAT_LINK_KONFIRMASI.md` untuk detail keamanan

### 🤝 Kontribusi
1. Fork repository
2. Buat feature branch
3. Commit changes
4. Push ke branch
5. Create Pull Request

## 📄 License

Sistem MedTrack menggunakan framework Laravel yang berlisensi [MIT license](https://opensource.org/licenses/MIT).
