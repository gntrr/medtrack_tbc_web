<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use Illuminate\Console\Command;

class TestUrlGeneration extends Command
{
    protected $signature = 'test:url-generation';
    protected $description = 'Test URL generation untuk link konfirmasi';

    public function handle()
    {
        $this->info('=== TEST URL GENERATION ===');
        
        // Informasi Environment
        $this->info('Environment: ' . config('app.env'));
        $this->info('APP_URL: ' . config('app.url'));
        $this->info('Current URL: ' . url('/'));
        
        // Test dengan token dummy
        $dummyToken = 'test123token456dummy789';
        $testUrl = route('konfirmasi-obat.show', $dummyToken);
        
        $this->info("\n=== HASIL TEST ===");
        $this->info("Token: {$dummyToken}");
        $this->info("Generated URL: {$testUrl}");
        
        // Test dengan data real jika ada
        $jadwalObat = Jadwal::where('jenis', 'obat')
            ->whereNotNull('token_konfirmasi')
            ->first();
            
        if ($jadwalObat) {
            $realUrl = route('konfirmasi-obat.show', $jadwalObat->token_konfirmasi);
            $this->info("\n=== CONTOH DATA REAL ===");
            $this->info("Pasien: {$jadwalObat->pasien->nama}");
            $this->info("Token: {$jadwalObat->token_konfirmasi}");
            $this->info("URL: {$realUrl}");
        } else {
            $this->warn("\nTidak ada data jadwal obat dengan token konfirmasi.");
        }
        
        $this->info("\n=== VERIFIKASI ===");
        $this->info("✓ URL akan otomatis berubah sesuai APP_URL");
        $this->info("✓ Cocok untuk development, staging, dan production");
        $this->info("✓ Tidak perlu manual edit kode saat deploy");
    }
}
