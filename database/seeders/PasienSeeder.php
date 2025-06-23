<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\User;
use Carbon\Carbon;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
        
        // Sample patients data with dates close to today
        $dataPasien = [
            [
                'nama' => 'Pasien A',
                'alamat' => 'Jl. Contoh No. 1',
                'nomor_telepon' => '6281234567890',
                'jadwal_pengobatan' => $today->copy()->subDays(21)->format('Y-m-d'), // 3 weeks ago
            ],
            [
                'nama' => 'Pasien B',
                'alamat' => 'Jl. Contoh No. 2',
                'nomor_telepon' => '6281234567891',
                'jadwal_pengobatan' => $today->copy()->subDays(14)->format('Y-m-d'), // 2 weeks ago
            ],
            [
                'nama' => 'Pasien C',
                'alamat' => 'Jl. Contoh No. 3',
                'nomor_telepon' => '6281234567892',
                'jadwal_pengobatan' => $today->copy()->subDays(7)->format('Y-m-d'),  // 1 week ago
            ],
            [
                'nama' => 'Pasien D',
                'alamat' => 'Jl. Contoh No. 4',
                'nomor_telepon' => '6281234567893',
                'jadwal_pengobatan' => $today->copy()->format('Y-m-d'),  // Today
            ],
            [
                'nama' => 'Pasien E',
                'alamat' => 'Jl. Contoh No. 5',
                'nomor_telepon' => '6281234567894',
                'jadwal_pengobatan' => $today->copy()->addDays(7)->format('Y-m-d'),  // 1 week from today
            ],
        ];
        
        foreach ($dataPasien as $data) {
            $pasien = Pasien::create($data);
            
            // Generate schedule for this patient
            $this->generateSchedule($pasien);
        }
    }
    
    /**
     * Generate schedule for a patient.
     */
    private function generateSchedule($pasien)
    {
        $startDate = Carbon::parse($pasien->jadwal_pengobatan);
        
        // Generate phase 1 (intensive) - every 7 days for 8 periods
        for ($k = 0; $k < 8; $k++) {
            $reminderDate = (clone $startDate)->addDays($k * 7);
            
            $pasien->jadwal()->create([
                'tanggal_waktu_pengingat' => $reminderDate->setTime(9, 0), // Set time to 9 AM
                'status' => 'menunggu',
                'fase' => 'intensif',
                'periode' => $k,
            ]);
        }
        
        // Generate phase 2 (continuation) - every 14 days for 8 periods
        $phase2Start = (clone $startDate)->addDays(7 * 8); // Start after phase 1 ends
        
        for ($m = 0; $m < 8; $m++) {
            $reminderDate = (clone $phase2Start)->addDays($m * 14);
            
            $pasien->jadwal()->create([
                'tanggal_waktu_pengingat' => $reminderDate->setTime(9, 0), // Set time to 9 AM
                'status' => 'menunggu',
                'fase' => 'lanjutan',
                'periode' => $m,
            ]);
        }
    }
}
