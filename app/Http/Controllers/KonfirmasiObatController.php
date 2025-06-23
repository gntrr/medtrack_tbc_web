<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Riwayat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KonfirmasiObatController extends Controller
{
    /**
     * Show confirmation page for medicine reminder.
     */
    public function show($token)
    {
        $jadwal = Jadwal::where('token_konfirmasi', $token)
            ->where('jenis', 'obat')
            ->with('pasien')
            ->firstOrFail();

        // Check if already confirmed
        if ($jadwal->status_konfirmasi !== 'belum') {
            return view('konfirmasi-obat.already-confirmed', [
                'jadwal' => $jadwal,
                'status' => $jadwal->status_konfirmasi
            ]);
        }

        // Check if confirmation is still valid (same day)
        if (!$jadwal->isKonfirmasiValid()) {
            return view('konfirmasi-obat.expired', ['jadwal' => $jadwal]);
        }

        return view('konfirmasi-obat.form', ['jadwal' => $jadwal]);
    }

    /**
     * Process medicine confirmation.
     */
    public function konfirmasi(Request $request, $token)
    {
        $jadwal = Jadwal::where('token_konfirmasi', $token)
            ->where('jenis', 'obat')
            ->with('pasien')
            ->firstOrFail();

        // Check if already confirmed
        if ($jadwal->status_konfirmasi !== 'belum') {
            return view('konfirmasi-obat.status', [
                'jadwal' => $jadwal,
                'status' => 'sudah',
                'message' => 'Anda sudah mengkonfirmasi minum obat untuk hari ini.'
            ]);
        }

        // Validate confirmation time
        $now = Carbon::now();
        $today = $now->toDateString();
        $jadwalDate = $jadwal->tanggal_waktu_pengingat->toDateString();

        if ($today !== $jadwalDate) {
            // Mark as late if not confirmed on the same day
            $jadwal->update([
                'status_konfirmasi' => 'terlambat',
                'tgl_waktu_konfirmasi' => $now,
            ]);

            // Create history record
            Riwayat::create([
                'jadwal_id' => $jadwal->id,
                'jenis_aktivitas' => 'konfirmasi_terlambat',
                'deskripsi' => 'Konfirmasi minum obat terlambat',
                'tanggal_waktu' => $now,
            ]);

            return view('konfirmasi-obat.status', [
                'jadwal' => $jadwal,
                'status' => 'terlambat',
                'message' => 'Konfirmasi terlambat. Mohon minum obat tepat waktu di hari berikutnya.'
            ]);
        }

        // Confirm on time
        $jadwal->update([
            'status_konfirmasi' => 'sudah',
            'tgl_waktu_konfirmasi' => $now,
        ]);

        // Create history record
        Riwayat::create([
            'jadwal_id' => $jadwal->id,
            'jenis_aktivitas' => 'konfirmasi_obat',
            'deskripsi' => 'Konfirmasi minum obat berhasil',
            'tanggal_waktu' => $now,
        ]);

        return view('konfirmasi-obat.status', [
            'jadwal' => $jadwal,
            'status' => 'sudah',
            'message' => 'Terima kasih! Konfirmasi Anda telah diterima.'
        ]);
    }

    /**
     * Show confirmation statistics for a patient.
     */
    public function statistik(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $pasienId = $request->get('pasien_id');
        
        $query = Jadwal::obat()
            ->whereDate('tanggal_waktu_pengingat', '>=', $startDate)
            ->whereDate('tanggal_waktu_pengingat', '<=', $endDate);
            
        if ($pasienId) {
            $query->where('pasien_id', $pasienId);
        }
        
        $jadwalObat = $query->with('pasien')->get();
        
        $statistik = [
            'total' => $jadwalObat->count(),
            'sudah' => $jadwalObat->where('status_konfirmasi', 'sudah')->count(),
            'terlambat' => $jadwalObat->where('status_konfirmasi', 'terlambat')->count(),
            'belum' => $jadwalObat->where('status_konfirmasi', 'belum')->count(),
        ];
        
        $statistik['persentase_kepatuhan'] = $statistik['total'] > 0 
            ? round(($statistik['sudah'] / $statistik['total']) * 100, 1) 
            : 0;
        
        return view('konfirmasi-obat.statistik', compact('statistik', 'jadwalObat', 'startDate', 'endDate'));
    }
}
