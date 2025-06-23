<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Riwayat Pengiriman') }}
            </h2>
            <a href="{{ route('riwayat.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi Pengiriman</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Waktu Pengiriman</p>
                            <p class="font-semibold">{{ $riwayat->waktu_pengiriman->format('d F Y - H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <p>
                                @if($riwayat->status === 'terkirim')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Terkirim
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Gagal
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Detail Jadwal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal dan Waktu Pengingat</p>
                            <p class="font-semibold">{{ $riwayat->jadwal->tanggal_waktu_pengingat->format('d F Y - H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Fase</p>
                            <p>
                                @if($riwayat->jadwal->fase === 'intensif')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Intensif
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Lanjutan
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Periode ke-</p>
                            <p class="font-semibold">{{ $riwayat->jadwal->periode + 1 }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Detail Jadwal</p>
                            <a href="{{ route('jadwal.show', $riwayat->jadwal) }}" class="text-blue-600 hover:text-blue-800">
                                Lihat Detail Jadwal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi Pasien</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nama Pasien</p>
                            <p class="font-semibold">{{ $riwayat->jadwal->pasien->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nomor Telepon</p>
                            <p class="font-semibold">{{ $riwayat->jadwal->pasien->nomor_telepon }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Detail Pasien</p>
                            <a href="{{ route('pasien.show', $riwayat->jadwal->pasien) }}" class="text-blue-600 hover:text-blue-800">
                                Lihat Data Pasien
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Konten Pesan</h3>
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <p class="whitespace-pre-line">{{ $riwayat->pesan }}</p>
                    </div>
                    
                    @if($riwayat->status === 'gagal')
                        <div class="mt-6">
                            <h4 class="font-semibold mb-2">Detail Respon Error</h4>
                            <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                                <pre class="text-sm text-red-800 overflow-x-auto">{{ json_encode(json_decode($riwayat->respons), JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @else
                        <div class="mt-6">
                            <h4 class="font-semibold mb-2">Detail Respon Sukses</h4>
                            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                <pre class="text-sm text-green-800 overflow-x-auto">{{ json_encode(json_decode($riwayat->respons), JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 