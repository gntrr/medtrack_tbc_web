# MEKANISME PENYESUAIAN DOMAIN HOSTING

## Overview
Sistem pengingat obat harian menggunakan mekanisme otomatis untuk menyesuaikan link konfirmasi dengan domain hosting yang digunakan. Ini memastikan bahwa link konfirmasi akan selalu menggunakan URL yang benar, baik di development, staging, maupun production.

## Cara Kerja

### 1. Konfigurasi Environment (`APP_URL`)
Link konfirmasi dibuat menggunakan Laravel's `route()` helper yang otomatis menggunakan `APP_URL` dari file `.env`:

```env
# Development
APP_URL=http://localhost:8000

# Staging
APP_URL=https://staging.example.com

# Production
APP_URL=https://yourdomain.com
```

### 2. Generasi Link di Job
Di file `app/Jobs/KirimPengingatObatHarian.php`:

```php
// Link konfirmasi otomatis menggunakan APP_URL
$linkKonfirmasi = route('konfirmasi-obat.show', $this->jadwal->token_konfirmasi);

// Contoh hasil:
// Development: http://localhost:8000/konfirmasi-obat/abc123def456
// Production: https://yourdomain.com/konfirmasi-obat/abc123def456
```

### 3. Logging URL untuk Monitoring
Job juga mencatat URL yang digenerate untuk monitoring:

```php
Log::info('Pengingat obat harian berhasil dikirim', [
    'jadwal_id' => $this->jadwal->id,
    'pasien_id' => $this->jadwal->pasien_id,
    'pasien_nama' => $namaPasien,
    'nomor_telepon' => $nomorTelepon,
    'link_konfirmasi' => $linkKonfirmasi  // URL lengkap tercatat di log
]);
```

## Testing dan Verifikasi

### 1. Command Test URL Generation
Jalankan command untuk memverifikasi URL generation:

```bash
php artisan test:url-generation
```

Output akan menampilkan:
- Environment saat ini
- APP_URL yang digunakan  
- Contoh URL yang digenerate
- Verifikasi dengan data real

### 2. Manual Verification
Anda juga bisa mengecek di browser developer tools atau log file untuk melihat URL yang digenerate saat pengiriman.

## Keuntungan Sistem Ini

### ✅ Otomatis
- Tidak perlu manual edit kode saat deploy
- Mengikuti konfigurasi environment secara otomatis
- Konsisten di semua environment

### ✅ Fleksibel
- Mendukung HTTP/HTTPS
- Mendukung subdomain
- Mendukung custom domain
- Mendukung port non-standard (development)

### ✅ Aman
- URL terikat dengan environment
- Token konfirmasi unik per jadwal
- Logging untuk audit trail

## Setup untuk Production

### 1. Update Environment
```env
APP_URL=https://yourdomain.com
```

### 2. Clear Cache (jika diperlukan)
```bash
php artisan config:cache
php artisan route:cache
```

### 3. Test Pengiriman
```bash
php artisan test:pengingat-obat --dry-run
```

## Contoh Hasil

### Development
```
Token: abc123def456ghi789
URL: http://localhost:8000/konfirmasi-obat/abc123def456ghi789
```

### Production
```
Token: abc123def456ghi789  
URL: https://medtrack.hospitalname.com/konfirmasi-obat/abc123def456ghi789
```

## Format Pesan WhatsApp

Pesan WhatsApp yang dikirim akan menggunakan URL yang sesuai dengan environment:

```
Halo [Nama Pasien], ini pengingat untuk minum obat TBC hari ini pukul [Waktu].

Silakan klik link berikut untuk mengonfirmasi bahwa Anda sudah minum obat:
[URL KONFIRMASI SESUAI DOMAIN]

Terima kasih, konfirmasi Anda telah diterima.
```

## Troubleshooting

### Jika URL Salah
1. Cek konfigurasi `APP_URL` di `.env`
2. Jalankan `php artisan config:clear`
3. Test dengan `php artisan test:url-generation`

### Jika Link Tidak Bisa Diakses
1. Pastikan domain sudah mengarah ke server
2. Cek konfigurasi web server (Apache/Nginx)
3. Pastikan routes sudah ter-cache dengan benar

## Kesimpulan

Sistem ini memastikan bahwa link konfirmasi akan selalu menggunakan domain yang benar tanpa perlu modifikasi kode, sehingga cocok untuk deployment di berbagai environment dengan mudah dan aman.
