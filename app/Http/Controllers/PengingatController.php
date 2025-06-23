<?php

namespace App\Http\Controllers;

use App\Jobs\KirimPengingatWhatsApp;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PengingatController extends Controller
{
    /**
     * Menampilkan daftar jadwal yang jatuh tempo
     */
    public function index(Request $request)
    {
        
        // Tentukan jumlah data per halaman (default: 25)
        $perPage = $request->input('per_page', 25);
        
        // Gunakan query langsung untuk mendapatkan semua jadwal dengan status menunggu
        // tanpa memperhatikan tanggal untuk sementara
        $jadwalJatuhTempo = Jadwal::where('status', 'menunggu')
            ->with('pasien')
            ->simplePaginate($perPage);
        
        return view('pengingat.index', [
            'jadwalJatuhTempo' => $jadwalJatuhTempo,
            'perPage' => $perPage,
            'totalJadwal' => $jadwalJatuhTempo->count()
        ]);
    }

    /**
     * Kirim pengingat WhatsApp untuk jadwal tertentu
     */
    public function kirimPengingat($id)
    {
        $jadwal = Jadwal::with('pasien')->findOrFail($id);
        
        try {
            KirimPengingatWhatsApp::dispatch($jadwal);
            
            return redirect()->back()
                ->with('success', "Pengingat berhasil dikirim ke pasien {$jadwal->pasien->nama}");
        } catch (\Exception $e) {
            Log::error('Gagal mengirim pengingat dari website', [
                'jadwal_id' => $jadwal->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', "Gagal mengirim pengingat: {$e->getMessage()}");
        }
    }

    /**
     * Kirim semua pengingat WhatsApp yang jatuh tempo
     */
    public function kirimSemuaPengingat()
    {
        $jadwalJatuhTempo = Jadwal::jatuhTempo()->with('pasien')->get();
        $count = $jadwalJatuhTempo->count();
        
        if ($count == 0) {
            return redirect()->back()
                ->with('info', 'Tidak ada pengingat yang perlu dikirim saat ini.');
        }
        
        $berhasil = 0;
        $gagal = 0;
        
        foreach ($jadwalJatuhTempo as $jadwal) {
            try {
                KirimPengingatWhatsApp::dispatch($jadwal);
                $berhasil++;
            } catch (\Exception $e) {
                Log::error('Gagal mengirim pengingat dari website', [
                    'jadwal_id' => $jadwal->id,
                    'error' => $e->getMessage()
                ]);
                $gagal++;
            }
        }
        
        $pesan = "Berhasil mengirim {$berhasil} pengingat";
        if ($gagal > 0) {
            $pesan .= ", {$gagal} pengingat gagal dikirim";
        }
        
        return redirect()->back()->with('success', $pesan);
    }
} 