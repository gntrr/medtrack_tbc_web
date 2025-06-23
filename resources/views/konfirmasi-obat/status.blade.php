<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Minum Obat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-8 text-center">
            @if ($status === 'sudah')
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 mb-2">Terima Kasih!</h1>
                <p class="text-gray-600 mb-6">{{ $message ?? 'Konfirmasi minum obat Anda telah berhasil diterima.' }}</p>
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <p class="text-sm text-green-800">
                        <strong>Pasien:</strong> {{ $jadwal->pasien->nama ?? '' }}<br>
                        <strong>Waktu konfirmasi:</strong> {{ $jadwal->tgl_waktu_konfirmasi ? $jadwal->tgl_waktu_konfirmasi->format('d F Y, H:i') : '' }}
                    </p>
                </div>
            @elseif ($status === 'terlambat')
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Terlambat</h1>
                <p class="text-gray-600 mb-6">{{ $message ?? 'Konfirmasi Anda tercatat sebagai terlambat. Mohon disiplin sesuai jadwal.' }}</p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Pasien:</strong> {{ $jadwal->pasien->nama ?? '' }}<br>
                        <strong>Jadwal:</strong> {{ $jadwal->tanggal_waktu_pengingat ? $jadwal->tanggal_waktu_pengingat->format('d F Y, H:i') : '' }}<br>
                        <strong>Konfirmasi:</strong> {{ $jadwal->tgl_waktu_konfirmasi ? $jadwal->tgl_waktu_konfirmasi->format('d F Y, H:i') : '' }}
                    </p>
                </div>
            @else
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-900 mb-2">Sudah Dikonfirmasi</h1>
                <p class="text-gray-600 mb-6">{{ $message ?? 'Anda sudah mengonfirmasi minum obat sebelumnya.' }}</p>
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Status:</strong> {{ ucfirst($jadwal->status_konfirmasi ?? '') }}<br>
                        @if($jadwal->tgl_waktu_konfirmasi ?? false)
                            <strong>Dikonfirmasi:</strong> {{ $jadwal->tgl_waktu_konfirmasi->format('d F Y, H:i') }}
                        @endif
                    </p>
                </div>
            @endif
            
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Sistem Penjadwalan Obat<br>
                    Puskesmas
                </p>
            </div>
        </div>
    </div>
</body>
</html>
