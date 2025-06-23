<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Kedaluwarsa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-8 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Kedaluwarsa</h1>
            <p class="text-gray-600 mb-6">
                Halo <strong>{{ $jadwal->pasien->nama }}</strong>,<br>
                Waktu konfirmasi untuk jadwal minum obat ini sudah terlewat.
            </p>
            
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                <div class="text-sm text-red-800">
                    <p><strong>Jadwal:</strong> {{ $jadwal->tanggal_waktu_pengingat->format('d F Y, H:i') }}</p>
                    <p><strong>Hari ini:</strong> {{ now()->format('d F Y, H:i') }}</p>
                    <p class="mt-2"><strong>Catatan:</strong> Konfirmasi hanya dapat dilakukan pada hari yang sama dengan jadwal.</p>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <strong>Peringatan:</strong> Jika Anda sudah minum obat tetapi lupa konfirmasi, 
                    harap tetap konsisten dengan jadwal pengobatan Anda. 
                    Hubungi petugas kesehatan jika ada pertanyaan.
                </p>
            </div>
            
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    Disiplin minum obat sangat penting untuk kesembuhan.<br>
                    Sistem Penjadwalan Obat - Puskesmas
                </p>
            </div>
        </div>
    </div>
</body>
</html>
