<?php

namespace App\Jobs;

use App\Models\Jadwal;
use App\Models\Riwayat;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KirimPengingatObatHarian implements ShouldQueue
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
            // Pastikan ini adalah jadwal obat harian
            if (!$this->jadwal->isObatHarian()) {
                Log::warning('Job ini hanya untuk pengingat obat harian', [
                    'jadwal_id' => $this->jadwal->id,
                    'jenis' => $this->jadwal->jenis
                ]);
                return;
            }

            // Pastikan jadwal belum terkirim
            if ($this->jadwal->status !== 'menunggu') {
                Log::info('Jadwal sudah terkirim atau gagal', [
                    'jadwal_id' => $this->jadwal->id,
                    'status' => $this->jadwal->status
                ]);
                return;
            }

            // Generate token konfirmasi jika belum ada
            if (!$this->jadwal->token_konfirmasi) {
                $this->jadwal->generateTokenKonfirmasi();
            }

            // Siapkan pesan WhatsApp dengan link konfirmasi
            $nomorTelepon = $this->jadwal->pasien->nomor_telepon;
            $namaPasien = $this->jadwal->pasien->nama;
            $waktuPengingat = $this->jadwal->tanggal_waktu_pengingat->format('H:i');
            $linkKonfirmasi = route('konfirmasi-obat.show', $this->jadwal->token_konfirmasi);

            $pesan = "Halo {$namaPasien}, ini pengingat untuk minum obat TBC hari ini pukul {$waktuPengingat}.\n\n";
            $pesan .= "Silakan klik link berikut untuk mengonfirmasi bahwa Anda sudah minum obat:\n";
            $pesan .= "{$linkKonfirmasi}\n\n";
            $pesan .= "Terima kasih, konfirmasi Anda telah diterima.";

            // Kirim pengingat via WhatsApp
            $result = $whatsAppService->kirimPesan($nomorTelepon, $pesan);
            
            if ($result['success']) {
                // Update status jadwal
                $this->jadwal->update(['status' => 'terkirim']);
                
                // Buat riwayat aktivitas
                Riwayat::create([
                    'jadwal_id' => $this->jadwal->id,
                    'jenis_aktivitas' => 'pengiriman_pengingat_obat',
                    'deskripsi' => 'Pengingat obat harian berhasil dikirim via WhatsApp',
                    'tanggal_waktu' => Carbon::now(),
                ]);                
                Log::info('Pengingat obat harian berhasil dikirim', [
                    'jadwal_id' => $this->jadwal->id,
                    'pasien_id' => $this->jadwal->pasien_id,
                    'pasien_nama' => $namaPasien,
                    'nomor_telepon' => $nomorTelepon,
                    'link_konfirmasi' => $linkKonfirmasi
                ]);
            } else {
                // Update status jadwal menjadi gagal
                $this->jadwal->update(['status' => 'gagal']);
                
                // Buat riwayat aktivitas
                Riwayat::create([
                    'jadwal_id' => $this->jadwal->id,
                    'jenis_aktivitas' => 'pengiriman_gagal',
                    'deskripsi' => 'Pengingat obat harian gagal dikirim: ' . $result['message'],
                    'tanggal_waktu' => Carbon::now(),
                ]);

                Log::error('Pengingat obat harian gagal terkirim', [
                    'jadwal_id' => $this->jadwal->id,
                    'pasien_id' => $this->jadwal->pasien_id,
                    'pasien_nama' => $namaPasien,
                    'nomor_telepon' => $nomorTelepon,
                    'error' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            // Update status jadwal menjadi gagal
            $this->jadwal->update(['status' => 'gagal']);
            
            Log::error('Error saat mengirim pengingat obat harian', [
                'jadwal_id' => $this->jadwal->id,
                'pasien_id' => $this->jadwal->pasien_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Ulangi job jika belum mencapai batas maksimum percobaan
            if ($this->attempts() < 3) {
                $this->release(60 * 5); // Coba lagi setelah 5 menit
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        // Update status jadwal menjadi gagal jika job gagal setelah beberapa percobaan
        $this->jadwal->update(['status' => 'gagal']);
        
        // Buat riwayat aktivitas
        Riwayat::create([
            'jadwal_id' => $this->jadwal->id,
            'jenis_aktivitas' => 'pengiriman_gagal',
            'deskripsi' => 'Job pengingat obat harian gagal setelah beberapa percobaan: ' . $exception->getMessage(),
            'tanggal_waktu' => Carbon::now(),
        ]);

        Log::error('Job pengingat obat harian gagal secara permanen', [
            'jadwal_id' => $this->jadwal->id,
            'pasien_id' => $this->jadwal->pasien_id,
            'error' => $exception->getMessage()
        ]);
    }
}
