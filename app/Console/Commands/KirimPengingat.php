<?php

namespace App\Console\Commands;

use App\Jobs\KirimPengingatWhatsApp;
use App\Models\Jadwal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KirimPengingat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kirim-pengingat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat WhatsApp ke pasien yang jadwalnya jatuh tempo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengiriman pengingat WhatsApp...');
        
        // Ambil semua jadwal yang jatuh tempo
        $jadwalJatuhTempo = Jadwal::jatuhTempo()->with('pasien')->get();
        
        $count = $jadwalJatuhTempo->count();
        $this->info("Ditemukan {$count} pengingat yang perlu dikirim.");
        
        if ($count == 0) {
            $this->info('Tidak ada pengingat yang perlu dikirim.');
            return 0;
        }
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        foreach ($jadwalJatuhTempo as $jadwal) {
            try {
                // Dispatch job untuk kirim pesan WhatsApp
                KirimPengingatWhatsApp::dispatch($jadwal);
                
                $this->info(" - Mengirim pengingat untuk pasien: {$jadwal->pasien->nama}");
            } catch (\Exception $e) {
                $this->error(" - Gagal mengirim pengingat untuk jadwal ID: {$jadwal->id}. Error: {$e->getMessage()}");
                Log::error('Error saat dispatch job pengingat WhatsApp', [
                    'jadwal_id' => $jadwal->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Selesai mengirim pengingat WhatsApp.');
        
        return 0;
    }
}
