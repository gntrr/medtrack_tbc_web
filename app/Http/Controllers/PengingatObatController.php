<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PengingatObatController extends Controller
{
    /**
     * Display medicine reminders for today.
     */
    public function index()
    {
        $today = Carbon::today();
        
        // Get today's medicine reminders
        $pengingatHariIni = Jadwal::obat()
            ->whereDate('tanggal_waktu_pengingat', $today)
            ->with('pasien')
            ->orderBy('tanggal_waktu_pengingat')
            ->get();
            
        // Get upcoming medicine reminders (next 7 days)
        $pengingatMendatang = Jadwal::obat()
            ->whereDate('tanggal_waktu_pengingat', '>', $today)
            ->whereDate('tanggal_waktu_pengingat', '<=', $today->copy()->addDays(7))
            ->with('pasien')
            ->orderBy('tanggal_waktu_pengingat')
            ->get();
            
        // Group by date
        $pengingatMendatangByDate = $pengingatMendatang->groupBy(function($jadwal) {
            return $jadwal->tanggal_waktu_pengingat->format('Y-m-d');
        });
        
        return view('pengingat-obat.index', compact('pengingatHariIni', 'pengingatMendatangByDate'));
    }

    /**
     * Show form to create daily medicine reminders for a patient.
     */
    public function create(Pasien $pasien)
    {
        return view('pengingat-obat.create', compact('pasien'));
    }
    
    /**
     * Store daily medicine reminders for a patient.
     */
    public function store(Request $request, Pasien $pasien)
    {
        $request->validate([
            'waktu_pengingat' => 'required|date_format:H:i',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi_hari' => 'required|integer|min:1|max:365',
        ]);
        
        $waktuPengingat = $request->waktu_pengingat;
        list($hour, $minute) = explode(':', $waktuPengingat);
        
        $tanggalMulai = Carbon::parse($request->tanggal_mulai)->setTime($hour, $minute);
        $durasiHari = $request->durasi_hari;
        
        // Create daily medicine reminders
        for ($i = 0; $i < $durasiHari; $i++) {
            $tanggalPengingat = $tanggalMulai->copy()->addDays($i);
              $jadwal = Jadwal::create([
                'pasien_id' => $pasien->id,
                'jenis' => 'obat',
                'tanggal_waktu_pengingat' => $tanggalPengingat,
                'status' => 'menunggu',
                'status_konfirmasi' => 'belum',
                'token_konfirmasi' => Str::random(32),
            ]);
        }
        
        return redirect()->route('pasien.show', $pasien)
            ->with('success', "Berhasil membuat {$durasiHari} jadwal pengingat obat harian untuk {$pasien->nama}.");
    }
    
    /**
     * Show details of a medicine reminder.
     */
    public function show(Jadwal $jadwal)
    {
        if (!$jadwal->isObatHarian()) {
            abort(404);
        }
        
        $jadwal->load('pasien', 'riwayat');
        
        return view('pengingat-obat.show', compact('jadwal'));
    }
    
    /**
     * Update status of medicine reminder.
     */
    public function updateStatus(Request $request, Jadwal $jadwal)
    {
        if (!$jadwal->isObatHarian()) {
            abort(404);
        }
        
        $request->validate([
            'status' => 'required|in:menunggu,terkirim,gagal',
        ]);
        
        $jadwal->update([
            'status' => $request->status,
        ]);
        
        return redirect()->back()
            ->with('success', 'Status pengingat obat berhasil diperbarui.');
    }
    
    /**
     * Reschedule a medicine reminder.
     */
    public function reschedule(Request $request, Jadwal $jadwal)
    {
        if (!$jadwal->isObatHarian()) {
            abort(404);
        }
        
        $request->validate([
            'tanggal_waktu_pengingat' => 'required|date',
        ]);
        
        $jadwal->update([
            'tanggal_waktu_pengingat' => $request->tanggal_waktu_pengingat,
            'status' => 'menunggu',
            'status_konfirmasi' => 'belum',
        ]);
        
        // Generate new token if needed
        if (!$jadwal->token_konfirmasi) {
            $jadwal->generateTokenKonfirmasi();
        }
        
        return redirect()->back()
            ->with('success', 'Jadwal pengingat obat berhasil diatur ulang.');
    }
    
    /**
     * Delete medicine reminder.
     */
    public function destroy(Jadwal $jadwal)
    {
        if (!$jadwal->isObatHarian()) {
            abort(404);
        }
        
        $jadwal->delete();
        
        return redirect()->back()
            ->with('success', 'Pengingat obat berhasil dihapus.');
    }
    
    /**
     * Bulk create medicine reminders for multiple patients.
     */
    public function bulkCreate()
    {
        $pasienAktif = Pasien::where('status', 'aktif')->get();
        
        return view('pengingat-obat.bulk-create', compact('pasienAktif'));
    }
    
    /**
     * Bulk store medicine reminders.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'pasien_ids' => 'required|array',
            'pasien_ids.*' => 'exists:pasien,id',
            'waktu_pengingat' => 'required|date_format:H:i',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi_hari' => 'required|integer|min:1|max:365',
        ]);
        
        $waktuPengingat = $request->waktu_pengingat;
        list($hour, $minute) = explode(':', $waktuPengingat);
        
        $tanggalMulai = Carbon::parse($request->tanggal_mulai)->setTime($hour, $minute);
        $durasiHari = $request->durasi_hari;
        $totalCreated = 0;
        
        foreach ($request->pasien_ids as $pasienId) {
            for ($i = 0; $i < $durasiHari; $i++) {
                $tanggalPengingat = $tanggalMulai->copy()->addDays($i);
                  Jadwal::create([
                    'pasien_id' => $pasienId,
                    'jenis' => 'obat',
                    'tanggal_waktu_pengingat' => $tanggalPengingat,
                    'status' => 'menunggu',
                    'status_konfirmasi' => 'belum',
                    'token_konfirmasi' => Str::random(32),
                ]);
                
                $totalCreated++;
            }
        }
        
        $jumlahPasien = count($request->pasien_ids);
        
        return redirect()->route('pengingat-obat.index')
            ->with('success', "Berhasil membuat {$totalCreated} jadwal pengingat obat untuk {$jumlahPasien} pasien.");
    }
}
