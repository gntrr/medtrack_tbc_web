<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use App\Jobs\KirimPengingatObatHarian;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestPengingatObat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pengingat-obat {pasien_id? : ID pasien untuk test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test pengingat obat harian (create dummy data dan dispatch job)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST PENGINGAT OBAT HARIAN ===');
        
        $pasienId = $this->argument('pasien_id');
        
        if ($pasienId) {
            $pasien = \App\Models\Pasien::find($pasienId);
            if (!$pasien) {
                $this->error("Pasien dengan ID {$pasienId} tidak ditemukan.");
                return;
            }
        } else {
            // Ambil pasien pertama yang tersedia
            $pasien = \App\Models\Pasien::first();
            if (!$pasien) {
                $this->error("Tidak ada pasien ditemukan. Buat pasien terlebih dahulu.");
                return;
            }
        }
        
        $this->info("Menggunakan pasien: {$pasien->nama} (ID: {$pasien->id})");
        
        // Buat jadwal pengingat obat untuk test
        $jadwal = Jadwal::create([
            'pasien_id' => $pasien->id,
            'jenis' => 'obat',
            'tanggal_waktu_pengingat' => now(),
            'status' => 'menunggu',
            'status_konfirmasi' => 'belum',
            'token_konfirmasi' => Str::random(32),
        ]);
        
        $this->info("✓ Jadwal test berhasil dibuat (ID: {$jadwal->id})");
        $this->info("✓ Token konfirmasi: {$jadwal->token_konfirmasi}");
        $this->info("✓ Link konfirmasi: " . route('konfirmasi-obat.show', $jadwal->token_konfirmasi));
        
        // Dispatch job
        $this->info("\nMengirim job ke queue...");
        KirimPengingatObatHarian::dispatch($jadwal);
        $this->info("✓ Job berhasil di-dispatch");
        
        $this->warn("\nCATATAN:");
        $this->warn("- Pastikan queue worker berjalan: php artisan queue:work");
        $this->warn("- Pastikan konfigurasi WhatsApp API sudah benar di .env");
        $this->warn("- Cek log di storage/logs/laravel.log untuk detail pengiriman");
        
        $this->info("\nTest selesai.");
        
        // Tampilkan informasi jadwal
        $this->info("\n=== INFORMASI JADWAL TEST ===");
        $this->info("ID Jadwal: {$jadwal->id}");
        $this->info("Pasien: {$pasien->nama}");
        $this->info("No. HP: {$pasien->nomor_telepon}");
        $this->info("Waktu: " . $jadwal->tanggal_waktu_pengingat->format('d/m/Y H:i'));
        $this->info("Status: {$jadwal->status}");
        $this->info("Konfirmasi: {$jadwal->status_konfirmasi}");
    }
}
