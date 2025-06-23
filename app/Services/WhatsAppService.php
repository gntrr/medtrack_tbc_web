<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Riwayat;
use App\Models\Jadwal;

class WhatsAppService
{
    protected $apiKey;
    protected $apiUrl;
    protected $senderNumber;

    public function __construct()
    {
        // Mengambil konfigurasi dari .env
        $this->apiKey = config('whatsapp.api_key');
        $this->apiUrl = config('whatsapp.api_url');
        $this->senderNumber = config('whatsapp.sender_number');
    }

    /**
     * Kirim pengingat obat melalui WhatsApp
     * 
     * @param Jadwal $jadwal
     * @return array
     */
    public function kirimPengingat(Jadwal $jadwal)
    {
        $pasien = $jadwal->pasien;
        $fase = $jadwal->fase === 'intensif' ? 'Intensif' : 'Lanjutan';
        $periode = $jadwal->periode + 1; // Periode mulai dari 0
        
        // Buat pesan
        $pesan = "Halo {$pasien->nama},\n\n";
        $pesan .= "Ini adalah pengingat minum obat Anda untuk fase {$fase} periode ke-{$periode}.\n";
        $pesan .= "Mohon segera ambil obat Anda di puskesmas terdekat.\n\n";
        $pesan .= "Terima kasih,\nPetugas Kesehatan";
        
        try {
            // Kirim pesan menggunakan WhatsApp API
            $response = $this->sendMessage($pasien->nomor_telepon, $pesan);
            
            // Simpan riwayat pengiriman
            $riwayat = Riwayat::create([
                'jadwal_id' => $jadwal->id,
                'waktu_pengiriman' => now(),
                'status' => $response['success'] ? 'terkirim' : 'gagal',
                'pesan' => $pesan,
                'respons' => json_encode($response),
            ]);
            
            // Update status jadwal
            $jadwal->update([
                'status' => $response['success'] ? 'terkirim' : 'gagal',
            ]);
            
            return [
                'success' => $response['success'],
                'message' => $response['success'] ? 'Pesan berhasil dikirim' : 'Gagal mengirim pesan',
                'data' => $response,
                'riwayat_id' => $riwayat->id,
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp API Error: ' . $e->getMessage());
            
            // Simpan riwayat kegagalan
            $riwayat = Riwayat::create([
                'jadwal_id' => $jadwal->id,
                'waktu_pengiriman' => now(),
                'status' => 'gagal',
                'pesan' => $pesan,
                'respons' => json_encode(['error' => $e->getMessage()]),
            ]);
            
            // Update status jadwal
            $jadwal->update([
                'status' => 'gagal',
            ]);
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'riwayat_id' => $riwayat->id,
            ];
        }
    }
    
    /**
     * Kirim pesan WhatsApp langsung dengan nomor dan pesan
     * 
     * @param string $nomorTelepon
     * @param string $pesan
     * @return array
     */
    public function kirimPesan($nomorTelepon, $pesan)
    {
        try {
            $response = $this->sendMessage($nomorTelepon, $pesan);
            
            return [
                'success' => $response['success'],
                'message' => $response['success'] ? 'Pesan berhasil dikirim' : 'Gagal mengirim pesan',
                'data' => $response,
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp kirimPesan Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Kirim pesan WhatsApp
     * 
     * @param string $nomorTelepon
     * @param string $pesan
     * @return array
     */
    protected function sendMessage($nomorTelepon, $pesan)
    {
        // Contoh implementasi menggunakan Fonnte API (populer di Indonesia)
        // Ganti dengan provider API WhatsApp yang Anda gunakan
        
        try {
            $response = Http::withoutVerifying() // Nonaktifkan verifikasi SSL
                ->withHeaders([
                    'Authorization' => $this->apiKey,
                ])->post($this->apiUrl, [
                    'target' => $nomorTelepon,
                    'message' => $pesan,
                    'countryCode' => '62', // Indonesia
                ]);
            
            return [
                'success' => $response->successful(),
                'data' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Send Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}