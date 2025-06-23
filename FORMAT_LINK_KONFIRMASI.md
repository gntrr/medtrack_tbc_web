# FORMAT LINK KONFIRMASI DAN VALIDASI KEAMANAN

## Format Link Konfirmasi

### Structure URL
```
{DOMAIN}/konfirmasi-obat/{TOKEN}
```

### Contoh Link Konfirmasi

#### Development
```
http://localhost:8000/konfirmasi-obat/Ko8m7n3P9qR2sT4uV6wX8yZ1aB5cD7eF
```

#### Staging
```
https://staging.medtrack.com/konfirmasi-obat/Ko8m7n3P9qR2sT4uV6wX8yZ1aB5cD7eF
```

#### Production
```
https://medtrack.hospital.com/konfirmasi-obat/Ko8m7n3P9qR2sT4uV6wX8yZ1aB5cD7eF
```

## Generasi Token Konfirmasi

### Method di Model Jadwal
```php
public function generateTokenKonfirmasi()
{
    $this->token_konfirmasi = Str::random(32);
    $this->save();
    return $this->token_konfirmasi;
}
```

### Karakteristik Token
- **Panjang**: 32 karakter
- **Format**: Alphanumeric (a-z, A-Z, 0-9)
- **Unique**: Setiap jadwal memiliki token unik
- **Random**: Menggunakan Laravel's `Str::random()`

## Validasi Keamanan

### 1. Validasi Token di Controller
```php
public function show($token)
{
    // Cari jadwal berdasarkan token
    $jadwal = Jadwal::where('token_konfirmasi', $token)
        ->where('jenis', 'obat')
        ->first();

    // Validasi: jadwal tidak ditemukan
    if (!$jadwal) {
        return view('konfirmasi-obat.expired', [
            'message' => 'Link konfirmasi tidak valid atau sudah kedaluwarsa.'
        ]);
    }

    // Validasi: jadwal sudah dikonfirmasi
    if ($jadwal->status === 'terkonfirmasi') {
        return view('konfirmasi-obat.already-confirmed', [
            'jadwal' => $jadwal,
            'waktu_konfirmasi' => $jadwal->updated_at
        ]);
    }

    // Validasi: batas waktu konfirmasi
    if (!$this->isKonfirmasiValid($jadwal)) {
        return view('konfirmasi-obat.expired', [
            'jadwal' => $jadwal,
            'message' => 'Batas waktu konfirmasi sudah habis.'
        ]);
    }

    // Valid - tampilkan form konfirmasi
    return view('konfirmasi-obat.form', compact('jadwal'));
}
```

### 2. Validasi Waktu Konfirmasi
```php
private function isKonfirmasiValid($jadwal)
{
    $batasKonfirmasi = $jadwal->tanggal_waktu_pengingat->addHours(12);
    return Carbon::now()->lessThanOrEqualTo($batasKonfirmasi);
}
```

### 3. Rate Limiting (Optional)
Untuk mencegah abuse, bisa ditambahkan rate limiting:

```php
// Di RouteServiceProvider atau routes/web.php
Route::middleware(['throttle:5,1'])->group(function () {
    Route::get('/konfirmasi-obat/{token}', [KonfirmasiObatController::class, 'show'])
        ->name('konfirmasi-obat.show');
    Route::post('/konfirmasi-obat/{token}', [KonfirmasiObatController::class, 'store'])
        ->name('konfirmasi-obat.store');
});
```

## Skenario Validasi

### ✅ Link Valid
```
Status: 200 OK
Action: Tampilkan form konfirmasi
View: konfirmasi-obat.form
```

### ❌ Token Tidak Ditemukan
```
Status: 200 OK (dengan pesan error)
Action: Tampilkan halaman expired
View: konfirmasi-obat.expired
Message: "Link konfirmasi tidak valid atau sudah kedaluwarsa."
```

### ❌ Sudah Dikonfirmasi
```
Status: 200 OK (dengan info)
Action: Tampilkan halaman already-confirmed
View: konfirmasi-obat.already-confirmed
Info: Waktu konfirmasi sebelumnya
```

### ❌ Waktu Kedaluwarsa
```
Status: 200 OK (dengan pesan error)
Action: Tampilkan halaman expired
View: konfirmasi-obat.expired
Message: "Batas waktu konfirmasi sudah habis."
```

## Format Pesan WhatsApp

### Template Pesan
```
Halo {NAMA_PASIEN}, ini pengingat untuk minum obat TBC hari ini pukul {WAKTU}.

Silakan klik link berikut untuk mengonfirmasi bahwa Anda sudah minum obat:
{LINK_KONFIRMASI}

Terima kasih, konfirmasi Anda telah diterima.
```

### Contoh Pesan Real
```
Halo Andi Wijaya, ini pengingat untuk minum obat TBC hari ini pukul 08:00.

Silakan klik link berikut untuk mengonfirmasi bahwa Anda sudah minum obat:
https://medtrack.hospital.com/konfirmasi-obat/Ko8m7n3P9qR2sT4uV6wX8yZ1aB5cD7eF

Terima kasih, konfirmasi Anda telah diterima.
```

## Keamanan Tambahan

### 1. HTTPS Only (Production)
```env
# Force HTTPS
APP_URL=https://yourdomain.com
FORCE_HTTPS=true
```

### 2. Token Expiration
Token konfirmasi valid selama 12 jam setelah pengiriman:
```php
// Jadwal pengingat: 08:00
// Batas konfirmasi: 20:00 (12 jam kemudian)
```

### 3. Single Use Token
Token hanya bisa digunakan sekali. Setelah konfirmasi berhasil:
- Status jadwal berubah menjadi 'terkonfirmasi'
- Link tidak bisa digunakan lagi
- Attempts berikutnya akan menampilkan halaman 'already-confirmed'

### 4. Logging untuk Audit
Setiap akses link konfirmasi dicatat:
```php
Log::info('Akses link konfirmasi', [
    'token' => $token,
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
    'timestamp' => Carbon::now()
]);
```

## Testing Link Konfirmasi

### 1. Generate Test Token
```bash
php artisan test:pengingat-obat --dry-run
```

### 2. Manual Test
```bash
curl -I https://yourdomain.com/konfirmasi-obat/TEST_TOKEN_HERE
```

### 3. Browser Test
Buka link di browser dan verifikasi:
- ✅ Link valid → Form konfirmasi muncul
- ❌ Link invalid → Halaman error muncul
- ❌ Link expired → Pesan kedaluwarsa muncul

## Best Practices

### ✅ DO
- Gunakan HTTPS untuk production
- Generate token yang cukup panjang (32+ karakter)
- Implementasi batas waktu konfirmasi
- Log semua aktivitas konfirmasi
- Validasi token di setiap request

### ❌ DON'T
- Jangan gunakan token yang mudah ditebak
- Jangan biarkan link aktif selamanya
- Jangan skip validasi keamanan
- Jangan expose token di log public
