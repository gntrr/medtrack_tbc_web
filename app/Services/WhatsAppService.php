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
        // Mengambil konfigurasi dari database settings terlebih dahulu,
        // fallback ke .env jika belum ada di database
        $this->apiKey = \App\Models\Setting::get('whatsapp_api_key', config('whatsapp.api_key'));
        $this->apiUrl = \App\Models\Setting::get('whatsapp_api_url', config('whatsapp.api_url'));
        $this->senderNumber = \App\Models\Setting::get('whatsapp_sender_number', config('whatsapp.sender_number'));
    }    /**
     * Kirim pengingat melalui WhatsApp (method utama)
     * 
     * @param Jadwal $jadwal
     * @return array
     */
    public function kirimPengingat(Jadwal $jadwal)
    {
        // Pilih method yang sesuai berdasarkan jenis pengingat
        if ($jadwal->jenis === 'obat') {
            return $this->kirimPengingatObat($jadwal);
        } elseif ($jadwal->jenis === 'kontrol') {
            return $this->kirimPengingatKontrol($jadwal);
        } else {
            // Fallback untuk jenis yang tidak dikenal
            return $this->kirimPengingatKontrol($jadwal);
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

    /**
     * Kirim pengingat obat harian melalui WhatsApp
     * 
     * @param Jadwal $jadwal
     * @return array
     */
    public function kirimPengingatObat(Jadwal $jadwal)
    {
        $pasien = $jadwal->pasien;
        $waktu = $jadwal->tanggal_waktu_pengingat->format('H:i');
        $tanggal = $jadwal->tanggal_waktu_pengingat->format('d F Y');
        
        // Buat pesan pengingat obat harian
        $pesan = "🔔 *Pengingat Minum Obat*\n\n";
        $pesan .= "Halo {$pasien->nama},\n\n";
        $pesan .= "Ini adalah pengingat untuk minum obat hari ini pukul {$waktu}.\n\n";
        
        // Tambahkan link konfirmasi untuk pengingat obat
        if ($jadwal->token_konfirmasi) {
            $linkKonfirmasi = url('/konfirmasi-obat/' . $jadwal->token_konfirmasi);
            $pesan .= "Setelah minum obat, silakan konfirmasi melalui link berikut:\n";
            $pesan .= $linkKonfirmasi . "\n\n";
        }
        
        $pesan .= "Tetap semangat dalam proses pengobatan! 💪\n\n";
        $pesan .= "Terima kasih,\n";
        $pesan .= "_Tim Kesehatan_";
        
        return $this->kirimPesanDenganRiwayat($jadwal, $pesan);
    }

    /**
     * Kirim pengingat kontrol melalui WhatsApp  
     * 
     * @param Jadwal $jadwal
     * @return array
     */
    public function kirimPengingatKontrol(Jadwal $jadwal)
    {
        $pasien = $jadwal->pasien;
        $fase = $jadwal->fase === 'intensif' ? 'Intensif' : 'Lanjutan';
        $periode = $jadwal->periode + 1; // Periode mulai dari 0
        
        // Buat pesan pengingat kontrol
        $pesan = "🏥 *Pengingat Kontrol Kesehatan*\n\n";
        $pesan .= "Halo {$pasien->nama},\n\n";
        $pesan .= "Ini adalah pengingat kontrol kesehatan Anda untuk fase {$fase} periode ke-{$periode}.\n\n";
        $pesan .= "📅 Jadwal kontrol: {$jadwal->tanggal_waktu_pengingat->format('d F Y')}\n";
        $pesan .= "🏥 Mohon datang ke fasilitas kesehatan untuk pemeriksaan rutin.\n\n";
        $pesan .= "Jangan lupa membawa:\n";
        $pesan .= "• Kartu identitas\n";
        $pesan .= "• Kartu BPJS/asuransi (jika ada)\n";
        $pesan .= "• Obat yang masih tersisa\n\n";
        $pesan .= "Terima kasih,\n";
        $pesan .= "_Tim Kesehatan_";
        
        return $this->kirimPesanDenganRiwayat($jadwal, $pesan);
    }

    public function sendReminderMessage(Jadwal $jadwal)
    {
        return $this->kirimPengingat($jadwal);
    }

    /**
     * Kirim pesan dengan pencatatan riwayat
     * 
     * @param Jadwal $jadwal
     * @param string $pesan
     * @return array
     */
    private function kirimPesanDenganRiwayat(Jadwal $jadwal, $pesan)
    {
        try {
            // Kirim pesan menggunakan WhatsApp API
            $response = $this->sendMessage($jadwal->pasien->nomor_telepon, $pesan);
            
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
}