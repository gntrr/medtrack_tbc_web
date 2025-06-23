<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pasien Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('pasien.store') }}">
                        @csrf
                        
                        <!-- Nama Pasien -->
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                            <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('nama') }}" required autofocus>
                            @error('nama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Alamat -->
                        <div class="mb-4">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Nomor Telepon -->
                        <div class="mb-4">
                            <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">+</span>
                                <input type="text" name="nomor_telepon" id="nomor_telepon" class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="6281234567890" value="{{ old('nomor_telepon') }}" required>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Format: 628xxxxxxxxxx (tanpa spasi atau karakter khusus)</p>
                            @error('nomor_telepon')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Tanggal Mulai Pengobatan -->
                        <div class="mb-6">
                            <label for="jadwal_pengobatan" class="block text-sm font-medium text-gray-700">Tanggal Mulai Pengobatan</label>
                            <input type="date" name="jadwal_pengobatan" id="jadwal_pengobatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('jadwal_pengobatan', date('Y-m-d')) }}" required>
                            @error('jadwal_pengobatan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('pasien.index') }}" class="text-gray-600 hover:text-gray-900">
                                Kembali
                            </a>
                            
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Simpan Pasien
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 