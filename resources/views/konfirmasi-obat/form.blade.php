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
        <div class="bg-blue-600 px-6 py-4">
            <h1 class="text-white text-lg font-semibold text-center">Konfirmasi Minum Obat</h1>
        </div>
        
        <div class="px-6 py-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.586V5L8 4z"></path>
                    </svg>
                </div>
                <p class="text-gray-600">Halo <strong>{{ $jadwal->pasien->nama }}</strong>,</p>
                <p class="text-gray-600 mt-2">
                    Ini pengingat untuk minum obat TBC hari ini pukul <strong>{{ $jadwal->tanggal_waktu_pengingat->format('H:i') }}</strong>.
                </p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <div class="text-sm">
                    <p class="font-medium text-blue-900">Informasi Jadwal:</p>
                    <div class="mt-2 space-y-1 text-blue-800">
                        <p><strong>Tanggal:</strong> {{ $jadwal->tanggal_waktu_pengingat->format('d F Y') }}</p>
                        <p><strong>Waktu:</strong> {{ $jadwal->tanggal_waktu_pengingat->format('H:i') }} WIB</p>
                        <p><strong>Status:</strong> Menunggu konfirmasi</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('konfirmasi-obat.konfirmasi', $jadwal->token_konfirmasi) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Ya, Saya Sudah Minum Obat
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Silakan klik tombol di atas untuk mengonfirmasi bahwa Anda sudah minum obat.<br>
                    Konfirmasi hanya dapat dilakukan pada hari yang sama.
                </p>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4">
            <p class="text-xs text-gray-500 text-center">
                <strong>Penting:</strong> Minum obat secara teratur sesuai jadwal untuk kesembuhan optimal.<br>
                Sistem Penjadwalan Obat - Puskesmas
            </p>
        </div>
    </div>
</body>
</html>
