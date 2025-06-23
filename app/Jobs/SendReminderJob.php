<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Jadwal;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class SendReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The schedule instance.
     *
     * @var \App\Models\Jadwal
     */
    protected $jadwal;

    /**
     * Create a new job instance.
     */
    public function __construct(Jadwal $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsAppService): void
    {
        try {
            // Reload the schedule to make sure it's still pending
            $this->jadwal->refresh();
            
            if ($this->jadwal->status !== 'menunggu') {
                Log::info('Jadwal sudah diproses: ' . $this->jadwal->id);
                return;
            }
            
            Log::info('Mengirim pengingat untuk jadwal: ' . $this->jadwal->id);
            
            // Send the reminder
            $result = $whatsAppService->sendReminderMessage($this->jadwal);
            
            Log::info('Pengingat terkirim untuk jadwal: ' . $this->jadwal->id, [
                'success' => $result['success']
            ]);
        } catch (\Exception $e) {
            Log::error('Error mengirim pengingat: ' . $e->getMessage(), [
                'jadwal_id' => $this->jadwal->id
            ]);
            
            // If there's an exception, mark the schedule as failed
            $this->jadwal->update(['status' => 'gagal']);
        }
    }
}
