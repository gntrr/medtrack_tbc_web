<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Display a listing of all histories.
     */
    public function index()
    {
        $riwayat = Riwayat::with(['jadwal.pasien'])
            ->latest()
            ->paginate(15);

        $pasienList = Pasien::all();
            
        return view('riwayat.index', compact('riwayat', 'pasienList'));
    }
    
    /**
     * Display a listing of histories for a patient.
     */
    public function riwayatPasien(Request $request, Pasien $pasien)
    {
        $riwayat = Riwayat::with(['jadwal'])
            ->whereHas('jadwal', function($query) use ($pasien) {
                $query->where('pasien_id', $pasien->id);
            })
            ->latest()
            ->paginate(15);
            
        // Handle JSON format request for AJAX calls
        if ($request->query('format') === 'json') {
            return response()->json($riwayat->map(function($item) {
                return [
                    'id' => $item->id,
                    'waktu_pengiriman' => $item->waktu_pengiriman->format('d M Y, H:i'),
                    'status' => $item->status,
                    'jadwal' => [
                        'fase' => $item->jadwal->fase ? ucfirst($item->jadwal->fase) : 'Obat Harian',
                        'periode' => $item->jadwal->periode
                    ],
                    'detail_url' => route('riwayat.show', $item)
                ];
            }));
        }
            
        return view('riwayat.pasien', compact('pasien', 'riwayat'));
    }
    
    /**
     * Show the details of a history record.
     */
    public function show(Riwayat $riwayat)
    {
        return view('riwayat.show', compact('riwayat'));
    }
}
