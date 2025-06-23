<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Sudah Dikonfirmasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-8 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-xl font-bold text-gray-900 mb-2">Sudah Dikonfirmasi</h1>
            <p class="text-gray-600 mb-6">
                Halo <strong>{{ $jadwal->pasien->nama }}</strong>,<br>
                Anda sudah mengonfirmasi minum obat untuk jadwal ini.
            </p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <div class="text-sm text-blue-800">
                    <p><strong>Jadwal:</strong> {{ $jadwal->tanggal_waktu_pengingat->format('d F Y, H:i') }}</p>
                    <p><strong>Status:</strong> 
                        @if($status === 'sudah')
                            <span class="text-green-600 font-medium">Sudah dikonfirmasi</span>
                        @elseif($status === 'terlambat')
                            <span class="text-yellow-600 font-medium">Terlambat</span>
                        @endif
                    </p>
                    @if($jadwal->tgl_waktu_konfirmasi)
                        <p><strong>Waktu konfirmasi:</strong> {{ $jadwal->tgl_waktu_konfirmasi->format('d F Y, H:i') }}</p>
                    @endif
                </div>
            </div>
            
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    Terima kasih atas kedisiplinan Anda dalam minum obat.<br>
                    Sistem Penjadwalan Obat - Puskesmas
                </p>
            </div>
        </div>
    </div>
</body>
</html>
