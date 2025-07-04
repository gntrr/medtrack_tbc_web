<?php

namespace Database\Factories;

use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Riwayat>
 */
class RiwayatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jadwal_id' => Jadwal::factory(),
            'waktu_pengiriman' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'status' => $this->faker->randomElement(['terkirim', 'gagal']),
            'pesan' => $this->faker->sentence(),
            'respons' => $this->faker->sentence(),
        ];
    }
}