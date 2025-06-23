<?php

namespace App\Jobs;

use App\Models\Jadwal;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class KirimPengingatWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            // Kirim pengingat via WhatsApp
            $result = $whatsAppService->kirimPengingat($this->jadwal);
            
            // Log hasil
            if ($result['success']) {
                Log::info('Pengingat WhatsApp berhasil dikirim', [
                    'jadwal_id' => $this->jadwal->id,
                    'pasien_id' => $this->jadwal->pasien_id,
                    'riwayat_id' => $result['riwayat_id']
                ]);
            } else {
                Log::error('Pengingat WhatsApp gagal terkirim', [
                    'jadwal_id' => $this->jadwal->id,
                    'pasien_id' => $this->jadwal->pasien_id,
                    'error' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengirim pengingat WhatsApp', [
                'jadwal_id' => $this->jadwal->id,
                'pasien_id' => $this->jadwal->pasien_id,
                'error' => $e->getMessage()
            ]);
            
            // Ulangi job jika belum mencapai batas maksimum percobaan
            if ($this->attempts() < 3) {
                $this->release(60 * 5); // Coba lagi setelah 5 menit
            }
        }
    }
}
