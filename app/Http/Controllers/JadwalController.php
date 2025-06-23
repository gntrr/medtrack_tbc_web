<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get today's schedules
        $today = Carbon::today();
        $jadwalHariIni = Jadwal::whereDate('tanggal_waktu_pengingat', $today)
            ->with('pasien')
            ->orderBy('tanggal_waktu_pengingat')
            ->get();
            
        // Get upcoming schedules (next 7 days)
        $jadwalMendatang = Jadwal::whereDate('tanggal_waktu_pengingat', '>', $today)
            ->whereDate('tanggal_waktu_pengingat', '<=', $today->copy()->addDays(7))
            ->with('pasien')
            ->orderBy('tanggal_waktu_pengingat')
            ->get();
            
        // Group by date
        $jadwalMendatangByDate = $jadwalMendatang->groupBy(function($jadwal) {
            return $jadwal->tanggal_waktu_pengingat->format('Y-m-d');
        });
        
        return view('jadwal.index', compact('jadwalHariIni', 'jadwalMendatangByDate'));
    }

    /**
     * Show the form for editing schedules for a patient.
     */
    public function editJadwalPasien(Pasien $pasien)
    {
        return view('jadwal.edit', compact('pasien'));
    }

    /**
     * Update schedules for a patient.
     */
    public function updateJadwalPasien(Request $request, Pasien $pasien)
    {
        $request->validate([
            'waktu_pengingat' => 'required|date_format:H:i',
            'frekuensi_intensif' => 'required|integer|min:1|max:30',
            'frekuensi_lanjutan' => 'required|integer|min:1|max:30',
        ]);
        
        // Get reminder time
        $waktuPengingat = $request->waktu_pengingat;
        list($hour, $minute) = explode(':', $waktuPengingat);
        
        // Update all schedules
        $jadwalIntensif = $pasien->jadwal()->where('fase', 'intensif')->orderBy('periode')->get();
        $jadwalLanjutan = $pasien->jadwal()->where('fase', 'lanjutan')->orderBy('periode')->get();
        
        $startDate = Carbon::parse($pasien->jadwal_pengobatan);
        
        // Update intensive phase schedules
        $frekuensiIntensif = $request->frekuensi_intensif;
        foreach ($jadwalIntensif as $index => $jadwal) {
            $reminderDate = (clone $startDate)->addDays($index * $frekuensiIntensif);
            $jadwal->update([
                'tanggal_waktu_pengingat' => $reminderDate->setTime($hour, $minute),
            ]);
        }
        
        // Update continuation phase schedules
        $frekuensiLanjutan = $request->frekuensi_lanjutan;
        $phase2Start = (clone $startDate)->addDays(count($jadwalIntensif) * $frekuensiIntensif);
        foreach ($jadwalLanjutan as $index => $jadwal) {
            $reminderDate = (clone $phase2Start)->addDays($index * $frekuensiLanjutan);
            $jadwal->update([
                'tanggal_waktu_pengingat' => $reminderDate->setTime($hour, $minute),
            ]);
        }
        
        return redirect()->route('pasien.show', $pasien)
            ->with('success', 'Jadwal pengingat berhasil diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        $jadwal->load('pasien', 'riwayat');
        
        return view('jadwal.show', compact('jadwal'));
    }

    /**
     * Update the status of the specified resource in storage.
     */
    public function updateStatus(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'status' => 'required|in:menunggu,terkirim,gagal',
        ]);
        
        $jadwal->update([
            'status' => $request->status,
        ]);
        
        return redirect()->back()
            ->with('success', 'Status jadwal berhasil diperbarui.');
    }

    /**
     * Reschedule a reminder.
     */
    public function reschedule(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'tanggal_waktu_pengingat' => 'required|date',
        ]);
        
        $jadwal->update([
            'tanggal_waktu_pengingat' => $request->tanggal_waktu_pengingat,
            'status' => 'menunggu',
        ]);
        
        return redirect()->back()
            ->with('success', 'Jadwal pengingat berhasil diatur ulang.');
    }
}
