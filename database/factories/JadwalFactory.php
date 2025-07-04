<?php

namespace Database\Factories;

use App\Models\Pasien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pasien_id' => Pasien::factory(),
            'jenis' => $this->faker->randomElement(['kontrol', 'obat']),
            'tanggal_waktu_pengingat' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'status' => $this->faker->randomElement(['menunggu', 'terkirim', 'gagal']),
            'status_konfirmasi' => 'belum',
            'token_konfirmasi' => $this->faker->uuid(),
            'fase' => $this->faker->randomElement(['intensif', 'lanjutan']),
            'periode' => $this->faker->numberBetween(0, 7),
        ];
    }
}