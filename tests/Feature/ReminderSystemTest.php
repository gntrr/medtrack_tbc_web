<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Models\Schedule;
use App\Jobs\SendReminderJob;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Queue;
use Carbon\Carbon;

class ReminderSystemTest extends TestCase
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
        $patient = Patient::create([
            'user_id' => $user->id,
            'nama' => 'Reminder Test Patient',
            'alamat' => 'Test Address',
            'nomor_telepon' => '628123456789',
            'jadwal_pengobatan' => now()->subDays(7), // Start date was 7 days ago
        ]);
        
        // Create a due schedule (status is 'menunggu' and time is in the past)
        $schedule = Schedule::create([
            'patient_id' => $patient->id,
            'tanggal_waktu_pengingat' => now()->subHour(), // 1 hour ago
            'status' => 'menunggu',
            'phase' => 'intensif',
            'period' => 1,
        ]);
        
        // Run the command
        $this->artisan('app:send-reminders')
            ->expectsOutput('All reminder jobs have been dispatched successfully.')
            ->assertExitCode(0);
        
        // Assert that the job was dispatched
        Queue::assertPushed(SendReminderJob::class, function ($job) use ($schedule) {
            return $job->schedule->id === $schedule->id;
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
                'history' => new \App\Models\History([
                    'schedule_id' => 1,
                    'waktu_pengiriman' => now(),
                    'status' => 'terkirim',
                ])
            ]);
        
        // Create a user
        $user = User::factory()->create();
        
        // Create a patient
        $patient = Patient::create([
            'user_id' => $user->id,
            'nama' => 'Job Test Patient',
            'alamat' => 'Test Address',
            'nomor_telepon' => '628123456789',
            'jadwal_pengobatan' => now()->subDays(7),
        ]);
        
        // Create a schedule
        $schedule = Schedule::create([
            'patient_id' => $patient->id,
            'tanggal_waktu_pengingat' => now()->subHour(),
            'status' => 'menunggu',
            'phase' => 'intensif',
            'period' => 1,
        ]);
        
        // Create and execute the job
        $job = new SendReminderJob($schedule);
        $job->handle($mockService);
        
        // Refresh the schedule from the database
        $schedule->refresh();
        
        // The status should now be 'terkirim'
        $this->assertEquals('terkirim', $schedule->status);
        
        // There should be a history record
        $this->assertDatabaseHas('histories', [
            'schedule_id' => $schedule->id,
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
        $patient = Patient::create([
            'user_id' => $user->id,
            'nama' => 'Due Test Patient',
            'alamat' => 'Test Address',
            'nomor_telepon' => '628123456789',
            'jadwal_pengobatan' => now()->subDays(7),
        ]);
        
        // Create a due schedule (time is in the past)
        $dueSchedule = Schedule::create([
            'patient_id' => $patient->id,
            'tanggal_waktu_pengingat' => now()->subHour(),
            'status' => 'menunggu',
            'phase' => 'intensif',
            'period' => 1,
        ]);
        
        // Create a future schedule (time is in the future)
        $futureSchedule = Schedule::create([
            'patient_id' => $patient->id,
            'tanggal_waktu_pengingat' => now()->addHour(),
            'status' => 'menunggu',
            'phase' => 'intensif',
            'period' => 2,
        ]);
        
        // Run the command
        $this->artisan('app:send-reminders');
        
        // Assert that a job was dispatched for the due schedule only
        Queue::assertPushed(SendReminderJob::class, function ($job) use ($dueSchedule) {
            return $job->schedule->id === $dueSchedule->id;
        });
        
        // Assert that no job was dispatched for the future schedule
        Queue::assertNotPushed(SendReminderJob::class, function ($job) use ($futureSchedule) {
            return $job->schedule->id === $futureSchedule->id;
        });
    }
}
