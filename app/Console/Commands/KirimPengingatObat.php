<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use App\Jobs\KirimPengingatObatHarian;
use Illuminate\Console\Command;
use Carbon\Carbon;

class KirimPengingatObat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengingat:kirim-obat {--test : Mode test untuk melihat jadwal yang akan diproses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat obat harian untuk jadwal yang jatuh tempo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isTest = $this->option('test');
        
        $this->info('Memulai proses pengiriman pengingat obat harian...');
        
        // Ambil jadwal obat harian yang jatuh tempo (waktu pengingat <= sekarang)
        $jadwalJatuhTempo = Jadwal::obat()
            ->where('status', 'menunggu')
            ->where('tanggal_waktu_pengingat', '<=', Carbon::now())
            ->with('pasien')
            ->get();
            
        if ($jadwalJatuhTempo->isEmpty()) {
            $this->info('Tidak ada jadwal pengingat obat yang jatuh tempo.');
            return;
        }
        
        $this->info("Ditemukan {$jadwalJatuhTempo->count()} jadwal pengingat obat yang jatuh tempo:");
        
        // Tampilkan daftar jadwal
        $headers = ['ID', 'Pasien', 'Telepon', 'Jadwal', 'Status'];
        $data = [];
        
        foreach ($jadwalJatuhTempo as $jadwal) {
            $data[] = [
                $jadwal->id,
                $jadwal->pasien->nama,
                $jadwal->pasien->nomor_telepon,
                $jadwal->tanggal_waktu_pengingat->format('d/m/Y H:i'),
                ucfirst($jadwal->status_konfirmasi)
            ];
        }
        
        $this->table($headers, $data);
        
        if ($isTest) {
            $this->warn('Mode test aktif - tidak ada pengingat yang akan dikirim.');
            return;
        }
        
        // Konfirmasi sebelum mengirim
        if (!$this->confirm('Apakah Anda yakin ingin mengirim pengingat untuk ' . $jadwalJatuhTempo->count() . ' jadwal?')) {
            $this->info('Operasi dibatalkan.');
            return;
        }
        
        $berhasil = 0;
        $gagal = 0;
        
        // Kirim pengingat untuk setiap jadwal
        $progressBar = $this->output->createProgressBar($jadwalJatuhTempo->count());
        $progressBar->start();
        
        foreach ($jadwalJatuhTempo as $jadwal) {
            try {
                // Dispatch job untuk mengirim pengingat
                KirimPengingatObatHarian::dispatch($jadwal);
                $berhasil++;
                
                $this->newLine();
                $this->info("✓ Job pengingat obat untuk {$jadwal->pasien->nama} berhasil di-dispatch.");
            } catch (\Exception $e) {
                $gagal++;
                $this->newLine();
                $this->error("✗ Gagal dispatch job untuk {$jadwal->pasien->nama}: {$e->getMessage()}");
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Ringkasan hasil
        $this->info("=== RINGKASAN ===");
        $this->info("Total jadwal: {$jadwalJatuhTempo->count()}");
        $this->info("Berhasil di-dispatch: {$berhasil}");
        $this->info("Gagal di-dispatch: {$gagal}");
        
        if ($berhasil > 0) {
            $this->info("Job telah di-dispatch ke queue. Pengiriman WhatsApp akan diproses oleh worker.");
            $this->warn("Pastikan queue worker berjalan dengan perintah: php artisan queue:work");
        }
        
        $this->info('Selesai.');
    }
}
