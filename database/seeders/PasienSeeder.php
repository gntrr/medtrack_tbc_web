<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\User;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini membuat:
     * - 3 pasien dengan data realistis
     * - Jadwal kontrol untuk setiap pasien (jenis: 'kontrol')
     * - Pengingat obat harian untuk setiap pasien (jenis: 'obat')
     * - Sample data history konfirmasi untuk testing
     */
    public function run(): void
    {
        // First, make sure we have a user (health worker)
        $user = User::firstOrCreate(
            ['username' => 'petugas1'],
            [
                'nama_petugas' => 'Petugas Kesehatan',
                'username' => 'petugas1',
                'password' => bcrypt('password123'),
            ]
        );
        
        // Get today's date to use as reference
        $today = Carbon::today();
        
        // Sample patients data - 3 pasien dengan jadwal mulai pengobatan yang realistis
        $dataPasien = [
            [
                'nama' => 'Andi Wijaya',
                'alamat' => 'Jl. Merdeka No. 45, Jakarta Pusat',
                'nomor_telepon' => '6281234567890',
                'jadwal_pengobatan' => $today->copy()->subDays(5)->format('Y-m-d'), // 5 hari lalu
            ],
            [
                'nama' => 'Siti Rahayu',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Selatan',
                'nomor_telepon' => '6285678901234',
                'jadwal_pengobatan' => $today->copy()->subDays(2)->format('Y-m-d'), // 2 hari lalu
            ],
            [
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Thamrin No. 67, Jakarta Pusat',
                'nomor_telepon' => '6287890123456',
                'jadwal_pengobatan' => $today->copy()->format('Y-m-d'), // Hari ini
            ],
        ];
        
        foreach ($dataPasien as $data) {
            $pasien = Pasien::create($data);
            
            // Generate jadwal kontrol
            $this->generateScheduleKontrol($pasien);
            
            // Generate pengingat obat harian
            $this->generatePengingatObat($pasien);
            
            $this->command->info("✓ Data dan jadwal dibuat untuk pasien: {$pasien->nama}");
        }
        
        $totalKontrol = Jadwal::where('jenis', 'kontrol')->count();
        $totalObat = Jadwal::where('jenis', 'obat')->count();
        $this->command->info("Total {$totalKontrol} jadwal kontrol dan {$totalObat} pengingat obat berhasil dibuat.");
    }
    
    /**
     * Generate jadwal kontrol untuk pasien.
     */
    private function generateScheduleKontrol($pasien)
    {
        $startDate = Carbon::parse($pasien->jadwal_pengobatan);
        
        // Generate phase 1 (intensive) - every 7 days for 3 periods (lebih sedikit)
        for ($k = 0; $k < 3; $k++) {
            $reminderDate = (clone $startDate)->addDays($k * 7);
            
            $pasien->jadwal()->create([
                'jenis' => 'kontrol',
                'tanggal_waktu_pengingat' => $reminderDate->setTime(9, 0),
                'status' => $k == 0 ? 'terkirim' : 'menunggu', // Jadwal pertama sudah terkirim
                'fase' => 'intensif',
                'periode' => $k,
            ]);
        }
        
        // Generate phase 2 (continuation) - every 14 days for 2 periods (lebih sedikit)
        $phase2Start = (clone $startDate)->addDays(7 * 3);
        
        for ($m = 0; $m < 2; $m++) {
            $reminderDate = (clone $phase2Start)->addDays($m * 14);
            
            $pasien->jadwal()->create([
                'jenis' => 'kontrol',
                'tanggal_waktu_pengingat' => $reminderDate->setTime(9, 0),
                'status' => 'menunggu',
                'fase' => 'lanjutan',
                'periode' => $m,
            ]);
        }
    }
    
    /**
     * Generate pengingat obat harian untuk pasien.
     */
    private function generatePengingatObat($pasien)
    {
        $today = Carbon::today();
        
        // Buat pengingat obat untuk 3 hari terakhir (sebagai history)
        for ($i = 2; $i >= 0; $i--) {
            $tanggalPengingat = $today->copy()->subDays($i)->setTime(8, 0, 0); // Pagi jam 8
            
            // Status konfirmasi bervariasi untuk data history
            $statusKonfirmasi = 'belum';
            $tglWaktuKonfirmasi = null;
            $status = 'terkirim';
            
            if ($i == 2) { // 2 hari lalu - sudah dikonfirmasi tepat waktu
                $statusKonfirmasi = 'sudah';
                $tglWaktuKonfirmasi = $tanggalPengingat->copy()->addMinutes(30);
            } elseif ($i == 1) { // 1 hari lalu - terlambat konfirmasi
                $statusKonfirmasi = 'terlambat';
                $tglWaktuKonfirmasi = $tanggalPengingat->copy()->addHours(4);
            }
            // Hari ini ($i == 0) - belum dikonfirmasi
            
            Jadwal::create([
                'pasien_id' => $pasien->id,
                'jenis' => 'obat',
                'tanggal_waktu_pengingat' => $tanggalPengingat,
                'status' => $status,
                'status_konfirmasi' => $statusKonfirmasi,
                'token_konfirmasi' => Str::random(32),
                'tgl_waktu_konfirmasi' => $tglWaktuKonfirmasi,
            ]);
        }
        
        // Buat pengingat untuk hari ini malam dan besok
        for ($i = 0; $i <= 1; $i++) {
            $tanggalPengingat = $today->copy()->addDays($i)->setTime(19, 0, 0); // Malam jam 7
            
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
    }
}
