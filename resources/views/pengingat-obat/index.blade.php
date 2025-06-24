<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Pengingat Obat Harian') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">Kelola jadwal pengingat minum obat untuk pasien</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-4 flex space-x-3">
                <a href="{{ route('pengingat-obat.bulk-create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Massal
                </a>
                <a href="{{ route('pasien.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Pilih Pasien
                </a>
                <a href="{{ route('konfirmasi-obat.statistik') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Statistik
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

    <!-- Pengingat Hari Ini -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Pengingat Hari Ini ({{ $pengingatHariIni->count() }})</h2>
        </div>
        
        @if($pengingatHariIni->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($pengingatHariIni as $pengingat)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.586V5L8 4z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $pengingat->pasien->nama }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $pengingat->tanggal_waktu_pengingat->format('H:i') }}
                                        • {{ $pengingat->pasien->nomor_telepon }}
                                    </div>
                                </div>
                            </div>                            <div class="flex items-center space-x-2">
                                <!-- Status Konfirmasi -->
                                @if($pengingat->status_konfirmasi === 'sudah')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Sudah Konfirmasi
                                    </span>
                                @elseif($pengingat->status_konfirmasi === 'terlambat')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Menunggu
                                    </span>
                                @endif

                                <!-- Status Pengiriman -->
                                @if($pengingat->status === 'terkirim')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Terkirim
                                    </span>
                                @elseif($pengingat->status === 'gagal')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Gagal
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Menunggu
                                    </span>
                                @endif

                                <!-- Tombol Kirim Manual (hanya untuk status menunggu) -->
                                @if($pengingat->status === 'menunggu')
                                    <form action="{{ route('pengingat-obat.kirim-manual', $pengingat) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Kirim pengingat WhatsApp ke {{ $pengingat->pasien->nama }}?')"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Kirim
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('pengingat-obat.show', $pengingat) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.586V5L8 4z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pengingat hari ini</h3>
                <p class="mt-1 text-sm text-gray-500">Semua pasien sudah menyelesaikan jadwal pengingat obat untuk hari ini.</p>
            </div>
        @endif
    </div>

    <!-- Pengingat Mendatang -->
    @if($pengingatMendatangByDate->count() > 0)
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Pengingat Mendatang (7 Hari ke Depan)</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($pengingatMendatangByDate as $tanggal => $pengingatList)
                    <div class="px-6 py-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">
                            {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
                            <span class="text-gray-500">({{ $pengingatList->count() }} pengingat)</span>
                        </h3>
                        <div class="space-y-2">
                            @foreach($pengingatList as $pengingat)
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-md">
                                    <div class="flex items-center space-x-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $pengingat->pasien->nama }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $pengingat->tanggal_waktu_pengingat->format('H:i') }}
                                        </div>
                                    </div>
                                    <a href="{{ route('pengingat-obat.show', $pengingat) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        Detail
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>                @endforeach
            </div>
        </div>
    @endif
        </div>
    </div>
</x-app-layout>
