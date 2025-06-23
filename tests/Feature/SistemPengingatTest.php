<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Jadwal;
use App\Jobs\SendReminderJob;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Queue;
use Carbon\Carbon;

class SistemPengingatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the command dispatches jobs for due reminders.
     */
    public function test_command_dispatches_jobs_for_due_reminders(): void
    {
        // Mock the queue
        Queue::fake();
        
        // Create a user
        $user = User::factory()->create();
        
        // Create a patient with a due reminder
        $pasien = Pasien::create([
            'user_id' => $user->id,
            'nama' => 'Pasien Pengingat',
            'alamat' => 'Alamat Test',
            'nomor_telepon' => '628123456789',
            'jadwal_pengobatan' => now()->subDays(7), // Start date was 7 days ago
        ]);
        
        // Create a due schedule (status is 'menunggu' and time is in the past)
        $jadwal = Jadwal::create([
            'pasien_id' => $pasien->id,
            'tanggal_waktu_pengingat' => now()->subHour(), // 1 hour ago
            'status' => 'menunggu',
            'fase' => 'intensif',
            'periode' => 1,
        ]);
        
        // Run the command
        $this->artisan('app:send-reminders')
            ->expectsOutput('Semua tugas pengingat telah berhasil dijalankan.')
            ->assertExitCode(0);
        
        // Assert that the job was dispatched
        Queue::assertPushed(SendReminderJob::class, function ($job) use ($jadwal) {
            return $job->jadwal->id === $jadwal->id;
        });
    }
    
    /**
     * Test that the reminder job updates the schedule status.
     */
    public function test_reminder_job_updates_schedule_status(): void
    {
        // Mock the WhatsAppService
        $mockService = $this->mock(WhatsAppService::class);
        $mockService->shouldReceive('sendReminderMessage')
            ->once()
            ->andReturn([
                'success' => true,
                'riwayat' => new \App\Models\Riwayat([
                    'jadwal_id' => 1,
                    'waktu_pengiriman' => now(),
                    'status' => 'terkirim',
                ])
            ]);
        
        // Create a user
        $user = User::factory()->create();
        
        // Create a patient
        $pasien = Pasien::create([
            'user_id' => $user->id,
            'nama' => 'Pasien Job Test',
            'alamat' => 'Alamat Test',
            'nomor_telepon' => '628123456789',
            'jadwal_pengobatan' => now()->subDays(7),
        ]);
        
        // Create a schedule
        $jadwal = Jadwal::create([
            'pasien_id' => $pasien->id,
            'tanggal_waktu_pengingat' => now()->subHour(),
            'status' => 'menunggu',
            'fase' => 'intensif',
            'periode' => 1,
        ]);
        
        // Create and execute the job
        $job = new SendReminderJob($jadwal);
        $job->handle($mockService);
        
        // Refresh the schedule from the database
        $jadwal->refresh();
        
        // The status should now be 'terkirim'
        $this->assertEquals('terkirim', $jadwal->status);
        
        // There should be a history record
        $this->assertDatabaseHas('riwayat', [
            'jadwal_id' => $jadwal->id,
            'status' => 'terkirim',
        ]);
    }
    
    /**
     * Test that only due reminders are processed.
     */
    public function test_only_due_reminders_are_processed(): void
    {
        // Mock the queue
        Queue::fake();
        
        // Create a user
        $user = User::factory()->create();
        
        // Create a patient
        $pasien = Pasien::create([
            'user_id' => $user->id,
            'nama' => 'Pasien Jatuh Tempo',
            'alamat' => 'Alamat Test',
            'nomor_telepon' => '628123456789',
            'jadwal_pengobatan' => now()->subDays(7),
        ]);
        
        // Create a due schedule (time is in the past)
        $jadwalJatuhTempo = Jadwal::create([
            'pasien_id' => $pasien->id,
            'tanggal_waktu_pengingat' => now()->subHour(),
            'status' => 'menunggu',
            'fase' => 'intensif',
            'periode' => 1,
        ]);
        
        // Create a future schedule (time is in the future)
        $jadwalMendatang = Jadwal::create([
            'pasien_id' => $pasien->id,
            'tanggal_waktu_pengingat' => now()->addHour(),
            'status' => 'menunggu',
            'fase' => 'intensif',
            'periode' => 2,
        ]);
        
        // Run the command
        $this->artisan('app:send-reminders');
        
        // Assert that a job was dispatched for the due schedule only
        Queue::assertPushed(SendReminderJob::class, function ($job) use ($jadwalJatuhTempo) {
            return $job->jadwal->id === $jadwalJatuhTempo->id;
        });
        
        // Assert that no job was dispatched for the future schedule
        Queue::assertNotPushed(SendReminderJob::class, function ($job) use ($jadwalMendatang) {
            return $job->jadwal->id === $jadwalMendatang->id;
        });
    }
}
