<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Pasien;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PengingatObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa pasien untuk contoh
        $pasiens = Pasien::limit(3)->get();
        
        if ($pasiens->isEmpty()) {
            $this->command->warn('Tidak ada pasien ditemukan. Jalankan seeder pasien terlebih dahulu.');
            return;
        }
        
        $this->command->info('Membuat sample data pengingat obat harian...');
        
        foreach ($pasiens as $pasien) {
            // Buat pengingat obat untuk 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $tanggalPengingat = Carbon::now()->subDays($i)->setTime(7, 0, 0);
                
                // Variasi status konfirmasi
                $statusKonfirmasi = 'belum';
                $tglWaktuKonfirmasi = null;
                
                if ($i > 1) { // Hari-hari sebelumnya sudah ada konfirmasi
                    if ($i % 3 == 0) {
                        $statusKonfirmasi = 'terlambat';
                        $tglWaktuKonfirmasi = $tanggalPengingat->copy()->addHours(8);
                    } else {
                        $statusKonfirmasi = 'sudah';
                        $tglWaktuKonfirmasi = $tanggalPengingat->copy()->addHours(1);
                    }
                }
                
                Jadwal::create([
                    'pasien_id' => $pasien->id,
                    'jenis' => 'obat',
                    'tanggal_waktu_pengingat' => $tanggalPengingat,
                    'status' => $i <= 1 ? 'menunggu' : 'terkirim',
                    'status_konfirmasi' => $statusKonfirmasi,
                    'token_konfirmasi' => Str::random(32),
                    'tgl_waktu_konfirmasi' => $tglWaktuKonfirmasi,
                ]);
            }
            
            // Buat pengingat untuk hari ini dan besok
            for ($i = 0; $i <= 1; $i++) {
                $tanggalPengingat = Carbon::now()->addDays($i)->setTime(19, 0, 0);
                
                Jadwal::create([
                    'pasien_id' => $pasien->id,
                    'jenis' => 'obat',
                    'tanggal_waktu_pengingat' => $tanggalPengingat,
                    'status' => 'menunggu',
                    'status_konfirmasi' => 'belum',
                    'token_konfirmasi' => Str::random(32),
                    'tgl_waktu_konfirmasi' => null,
                ]);
            }
            
            $this->command->info("✓ Dibuat pengingat obat untuk pasien: {$pasien->nama}");
        }
        
        $totalCreated = Jadwal::where('jenis', 'obat')->count();
        $this->command->info("Total {$totalCreated} jadwal pengingat obat harian berhasil dibuat.");
    }
}
