<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pasien;
use App\Models\Jadwal;
use App\Models\Riwayat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasienShowTabTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $pasien;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->pasien = Pasien::factory()->create([
            'jadwal_pengobatan' => now(),
        ]);
    }

    /** @test */
    public function it_shows_pasien_detail_page_with_tabs()
    {
        $response = $this->actingAs($this->user)
            ->get(route('pasien.show', $this->pasien));

        $response->assertStatus(200);
        $response->assertSee('Fase Intensif');
        $response->assertSee('Fase Lanjutan');
        $response->assertSee('Riwayat Pengingat');
    }

    /** @test */
    public function it_returns_json_response_for_riwayat_ajax_request()
    {
        // Create some jadwal and riwayat data
        $jadwal = Jadwal::factory()->create([
            'pasien_id' => $this->pasien->id,
            'fase' => 'intensif',
            'periode' => 0,
        ]);

        $riwayat = Riwayat::factory()->create([
            'jadwal_id' => $jadwal->id,
            'status' => 'terkirim',
            'waktu_pengiriman' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('pasien.riwayat', $this->pasien) . '?format=json');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'waktu_pengiriman',
                'status',
                'jadwal' => [
                    'fase',
                    'periode'
                ],
                'detail_url'
            ]
        ]);
    }

    /** @test */
    public function it_shows_different_content_for_each_tab()
    {
        // Create jadwal for both phases
        $jadwalIntensif = Jadwal::factory()->create([
            'pasien_id' => $this->pasien->id,
            'fase' => 'intensif',
            'periode' => 0,
        ]);

        $jadwalLanjutan = Jadwal::factory()->create([
            'pasien_id' => $this->pasien->id,
            'fase' => 'lanjutan',
            'periode' => 0,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('pasien.show', $this->pasien));

        $response->assertStatus(200);
        
        // Check that both phase data are loaded in their respective tab content
        $response->assertSee('fase-intensif-content');
        $response->assertSee('fase-lanjutan-content');
        $response->assertSee('riwayat-pengingat-content');
    }
}