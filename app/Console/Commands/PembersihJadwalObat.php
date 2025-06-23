<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use App\Models\Riwayat;
use Illuminate\Console\Command;
use Carbon\Carbon;

class PembersihJadwalObat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengingat:bersihkan-obat {--days=30 : Hapus jadwal obat lebih lama dari X hari} {--test : Mode test tanpa menghapus data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bersihkan jadwal obat lama dan tandai konfirmasi terlambat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $isTest = $this->option('test');
        
        $this->info("Memulai pembersihan jadwal obat (lebih lama dari {$days} hari)...");
        
        // 1. Tandai konfirmasi yang terlambat
        $this->info('1. Memeriksa konfirmasi yang terlambat...');
        $this->tandaiKonfirmasiTerlambat($isTest);
        
        // 2. Hapus jadwal lama
        $this->info("2. Menghapus jadwal obat lebih lama dari {$days} hari...");
        $this->hapusJadwalLama($days, $isTest);
        
        $this->info('Pembersihan selesai.');
    }
    
    /**
     * Tandai konfirmasi yang terlambat.
     */
    private function tandaiKonfirmasiTerlambat($isTest = false)
    {
        $kemarin = Carbon::yesterday()->endOfDay();
        
        // Cari jadwal obat yang belum dikonfirmasi dan sudah lewat dari kemarin
        $jadwalTerlambat = Jadwal::obat()
            ->where('status_konfirmasi', 'belum')
            ->where('tanggal_waktu_pengingat', '<', $kemarin)
            ->with('pasien')
            ->get();
            
        if ($jadwalTerlambat->isEmpty()) {
            $this->info('   Tidak ada konfirmasi yang terlambat.');
            return;
        }
        
        $this->info("   Ditemukan {$jadwalTerlambat->count()} konfirmasi terlambat:");
        
        $headers = ['ID', 'Pasien', 'Jadwal', 'Status'];
        $data = [];
        
        foreach ($jadwalTerlambat as $jadwal) {
            $data[] = [
                $jadwal->id,
                $jadwal->pasien->nama,
                $jadwal->tanggal_waktu_pengingat->format('d/m/Y H:i'),
                $jadwal->status_konfirmasi
            ];
        }
        
        $this->table($headers, $data);
        
        if ($isTest) {
            $this->warn('   Mode test - tidak ada perubahan status.');
            return;
        }
        
        $diperbarui = 0;
        
        foreach ($jadwalTerlambat as $jadwal) {
            // Update status menjadi terlambat
            $jadwal->update([
                'status_konfirmasi' => 'terlambat',
                'tgl_waktu_konfirmasi' => Carbon::now()
            ]);
            
            // Buat riwayat
            Riwayat::create([
                'jadwal_id' => $jadwal->id,
                'jenis_aktivitas' => 'konfirmasi_terlambat_otomatis',
                'deskripsi' => 'Status konfirmasi diubah menjadi terlambat secara otomatis',
                'tanggal_waktu' => Carbon::now()
            ]);
            
            $diperbarui++;
        }
        
        $this->info("   ✓ {$diperbarui} jadwal berhasil ditandai sebagai terlambat.");
    }
    
    /**
     * Hapus jadwal obat yang lama.
     */
    private function hapusJadwalLama($days, $isTest = false)
    {
        $batasWaktu = Carbon::now()->subDays($days);
        
        // Cari jadwal obat lama
        $jadwalLama = Jadwal::obat()
            ->where('tanggal_waktu_pengingat', '<', $batasWaktu)
            ->with('pasien')
            ->get();
            
        if ($jadwalLama->isEmpty()) {
            $this->info("   Tidak ada jadwal obat lebih lama dari {$days} hari.");
            return;
        }
        
        $this->info("   Ditemukan {$jadwalLama->count()} jadwal obat lama:");
        
        // Kelompokkan berdasarkan status konfirmasi
        $groupedByStatus = $jadwalLama->groupBy('status_konfirmasi');
        foreach ($groupedByStatus as $status => $jadwals) {
            $this->info("   - {$status}: {$jadwals->count()} jadwal");
        }
        
        if ($isTest) {
            $this->warn('   Mode test - tidak ada jadwal yang dihapus.');
            return;
        }
        
        // Konfirmasi sebelum menghapus
        if (!$this->confirm("   Apakah Anda yakin ingin menghapus {$jadwalLama->count()} jadwal obat lama?")) {
            $this->info('   Penghapusan dibatalkan.');
            return;
        }
        
        $terhapus = 0;
        
        foreach ($jadwalLama as $jadwal) {
            try {
                // Hapus riwayat terkait terlebih dahulu
                $jadwal->riwayat()->delete();
                
                // Hapus jadwal
                $jadwal->delete();
                $terhapus++;
            } catch (\Exception $e) {
                $this->error("   ✗ Gagal menghapus jadwal ID {$jadwal->id}: {$e->getMessage()}");
            }
        }
        
        $this->info("   ✓ {$terhapus} jadwal obat lama berhasil dihapus.");
    }
}
