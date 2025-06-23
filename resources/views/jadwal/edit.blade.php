<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Jadwal Pengobatan') }} - {{ $pasien->nama }}
            </h2>
            <a href="{{ route('pasien.show', $pasien) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-semibold">Terjadi Kesalahan:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pasien.jadwal.update', $pasien) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Informasi Pasien -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h3 class="font-semibold mb-4">Informasi Pasien</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Nama Pasien</p>
                                    <p class="font-semibold">{{ $pasien->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Tanggal Mulai Pengobatan</p>
                                    <p class="font-semibold">{{ $pasien->jadwal_pengobatan->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pengaturan Jadwal -->
                        <div>
                            <h3 class="font-semibold mb-4">Pengaturan Jadwal Pengingat</h3>
                            
                            <div class="mb-4">
                                <label for="waktu_pengingat" class="block text-gray-700 text-sm font-bold mb-2">
                                    Waktu Pengingat (untuk semua jadwal)
                                </label>
                                <input 
                                    type="time" 
                                    name="waktu_pengingat" 
                                    id="waktu_pengingat"
                                    value="09:00" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                                <p class="text-sm text-gray-600 mt-1">
                                    Waktu ini akan digunakan untuk semua jadwal pengingat.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label for="frekuensi_intensif" class="block text-gray-700 text-sm font-bold mb-2">
                                        Fase Intensif - Frekuensi (hari)
                                    </label>
                                    <input 
                                        type="number" 
                                        name="frekuensi_intensif" 
                                        id="frekuensi_intensif"
                                        value="7" 
                                        min="1" 
                                        max="30"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        required>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Setiap berapa hari sekali pengingat akan dikirim pada fase intensif (default: 7 hari).
                                    </p>
                                </div>
                                <div>
                                    <label for="frekuensi_lanjutan" class="block text-gray-700 text-sm font-bold mb-2">
                                        Fase Lanjutan - Frekuensi (hari)
                                    </label>
                                    <input 
                                        type="number" 
                                        name="frekuensi_lanjutan" 
                                        id="frekuensi_lanjutan"
                                        value="14" 
                                        min="1" 
                                        max="30"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        required>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Setiap berapa hari sekali pengingat akan dikirim pada fase lanjutan (default: 14 hari).
                                    </p>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Perhatian:</strong> Mengubah frekuensi jadwal akan mengatur ulang semua jadwal pengingat pasien ini berdasarkan tanggal mulai pengobatan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 