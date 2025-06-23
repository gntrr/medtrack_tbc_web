<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasien = Pasien::latest()->paginate(10);
        return view('pasien.index', compact('pasien'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|regex:/^628[0-9]{8,11}$/',
            'jadwal_pengobatan' => 'required|date',
        ], [
            'nomor_telepon.regex' => 'Nomor WhatsApp harus diawali dengan 62 (format internasional) contoh: 628123456789'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pasien = Pasien::create($request->all());
        
        // Generate treatment schedule
        $this->generateSchedule($pasien);

        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasien $pasien)
    {
        $jadwalIntensif = $pasien->jadwal()
            ->where('fase', 'intensif')
            ->orderBy('periode')
            ->get();
            
        $jadwalLanjutan = $pasien->jadwal()
            ->where('fase', 'lanjutan')
            ->orderBy('periode')
            ->get();
            
        return view('pasien.show', compact(
            'pasien', 
            'jadwalIntensif', 
            'jadwalLanjutan'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasien $pasien)
    {
        return view('pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pasien $pasien)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|regex:/^628[0-9]{8,11}$/',
            'jadwal_pengobatan' => 'required|date',
        ], [
            'nomor_telepon.regex' => 'Nomor WhatsApp harus diawali dengan 62 (format internasional) contoh: 628123456789'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if treatment start date has changed
        $oldStartDate = $pasien->jadwal_pengobatan;
        $pasien->update($request->all());
        
        // If treatment start date has changed, regenerate schedules
        if ($oldStartDate != $request->jadwal_pengobatan) {
            // Delete old schedules
            $pasien->jadwal()->delete();
            
            // Regenerate schedules
            $this->generateSchedule($pasien);
        }

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasien $pasien)
    {
        // This will cascade delete all schedules and histories
        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil dihapus.');
    }
    
    /**
     * Generate schedule for a patient.
     */
    private function generateSchedule(Pasien $pasien)
    {
        $startDate = Carbon::parse($pasien->jadwal_pengobatan);
        
        // Generate phase 1 (intensive) - every 7 days for 8 periods
        for ($k = 0; $k < 8; $k++) {
            $reminderDate = (clone $startDate)->addDays($k * 7);
            
            $pasien->jadwal()->create([
                'jenis' => 'kontrol',
                'tanggal_waktu_pengingat' => $reminderDate->setTime(9, 0), // Set time to 9 AM
                'status' => 'menunggu',
                'fase' => 'intensif',
                'periode' => $k,
            ]);
        }
        
        // Generate phase 2 (continuation) - every 14 days for 8 periods
        $phase2Start = (clone $startDate)->addDays(7 * 8); // Start after phase 1 ends
        
        for ($m = 0; $m < 8; $m++) {
            $reminderDate = (clone $phase2Start)->addDays($m * 14);
            
            $pasien->jadwal()->create([
                'jenis' => 'kontrol',
                'tanggal_waktu_pengingat' => $reminderDate->setTime(9, 0), // Set time to 9 AM
                'status' => 'menunggu',
                'fase' => 'lanjutan',
                'periode' => $m,
            ]);
        }

         // Tambahan: Jadwal harian minum obat
        for ($i = 0; $i < 60; $i++) {
            $dailyReminder = (clone $startDate)->addDays($i)->setTime(7, 0); // Set time to 7 AM

            $pasien->jadwal()->create([
                'jenis' => 'obat',
                'tanggal_waktu_pengingat' => $dailyReminder,
                'status' => 'menunggu',
                'status_konfirmasi' => 'belum',
                'token_konfirmasi' => \Str::uuid(),
                'fase' => null,
                'periode' => null,
            ]);
        }
    }
}
