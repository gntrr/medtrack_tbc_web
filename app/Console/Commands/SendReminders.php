<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jadwal;
use App\Jobs\SendReminderJob;
use Illuminate\Support\Facades\Log;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat WhatsApp untuk jadwal pengobatan yang jatuh tempo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses pengiriman pengingat...');
        
        // Get all pending schedules that are due
        $jadwalJatuhTempo = Jadwal::jatuhTempo()->get();
        
        $count = $jadwalJatuhTempo->count();
        $this->info("Ditemukan {$count} pengingat yang perlu dikirim.");
        
        if ($count === 0) {
            $this->info('Tidak ada pengingat yang perlu dikirim saat ini.');
            return 0;
        }
        
        // Process each schedule by dispatching a job
        foreach ($jadwalJatuhTempo as $jadwal) {
            $this->info("Menjalankan tugas untuk jadwal ID: {$jadwal->id}");
            
            // Log that we're processing this schedule
            Log::info('Menjalankan tugas pengingat', [
                'jadwal_id' => $jadwal->id,
                'pasien_id' => $jadwal->pasien_id,
                'waktu_pengingat' => $jadwal->tanggal_waktu_pengingat,
            ]);
            
            // Dispatch the job to the queue
            SendReminderJob::dispatch($jadwal);
        }
        
        $this->info('Semua tugas pengingat telah berhasil dijalankan.');
        
        return 0;
    }
}
