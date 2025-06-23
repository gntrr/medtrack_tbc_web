<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Jadwal;
use App\Models\Riwayat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index()
    {
        // Get total patients count
        $totalPasien = Pasien::count();
        
        // Get active patients (patients with pending schedules)
        $pasienAktif = Pasien::whereHas('jadwal', function($query) {
                $query->where('status', 'menunggu');
            })
            ->count();
            
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
        
        // Get failed notifications from the last 7 days
        $notifikasiGagal = Riwayat::with(['jadwal.pasien'])
            ->where('status', 'gagal')
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(5)
            ->get();
            
        // Get compliance rate for each patient
        $pasien = Pasien::all();
        $dataKepatuhan = [];
        
        foreach ($pasien as $p) {
            $totalJadwal = $p->jadwal()
                ->where('status', '!=', 'menunggu')
                ->count();
                
            if ($totalJadwal > 0) {
                $jadwalBerhasil = $p->jadwal()
                    ->where('status', 'terkirim')
                    ->count();
                    
                $tingkatKepatuhan = round(($jadwalBerhasil / $totalJadwal) * 100);
                
                $dataKepatuhan[] = [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'tingkat' => $tingkatKepatuhan,
                ];
            }
        }
        
        return view('dashboard', compact(
            'totalPasien', 
            'pasienAktif', 
            'jadwalHariIni', 
            'notifikasiGagal',
            'dataKepatuhan',
            'jadwalMendatangByDate'
        ));
    }
}
