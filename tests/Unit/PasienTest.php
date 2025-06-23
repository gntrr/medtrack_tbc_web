<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasienTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test that the phone number is encrypted when stored.
     */
    public function test_phone_number_is_encrypted(): void
    {
        // Create a user first
        $user = User::factory()->create();
        
        // Create a patient with a phone number
        $pasien = Pasien::create([
            'user_id' => $user->id,
            'nama' => 'Test Patient',
            'alamat' => 'Test Address',
            'nomor_telepon' => '628123456789',
            'jadwal_pengobatan' => now(),
        ]);
        
        // Get the raw value from the database
        $rawPhoneNumber = \DB::table('pasien')->where('id', $pasien->id)->value('nomor_telepon');
        
        // The raw value should be different from the original phone number
        $this->assertNotEquals('628123456789', $rawPhoneNumber);
        
        // But when accessed through the model, it should be decrypted
        $this->assertEquals('628123456789', $pasien->nomor_telepon);
    }
    
    /**
     * Test that schedules are created automatically for a new patient.
     */
    public function test_schedules_are_generated_for_new_patient(): void
    {
        // Create a user first
        $user = User::factory()->create();
        
        // Create a patient controller to handle the patient creation with schedules
        $controller = new \App\Http\Controllers\PasienController();
        
        // Simulate a patient creation
        $pasien = Pasien::create([
            'user_id' => $user->id,
            'nama' => 'Schedule Test Patient',
            'alamat' => 'Test Address',
            'nomor_telepon' => '628123456789',
            'jadwal_pengobatan' => now(),
        ]);
        
        // Generate schedules via reflection to access the private method
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('generateSchedule');
        $method->setAccessible(true);
        $method->invoke($controller, $pasien);
        
        // The patient should have 16 schedules (8 for intensive phase, 8 for continuation phase)
        $this->assertEquals(16, $pasien->jadwal()->count());
        
        // Check intensive phase schedules
        $jadwalIntensif = $pasien->jadwal()->where('fase', 'intensif')->count();
        $this->assertEquals(8, $jadwalIntensif);
        
        // Check continuation phase schedules
        $jadwalLanjutan = $pasien->jadwal()->where('fase', 'lanjutan')->count();
        $this->assertEquals(8, $jadwalLanjutan);
    }
}
