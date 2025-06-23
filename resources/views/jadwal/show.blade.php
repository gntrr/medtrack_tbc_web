<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Jadwal Pengingat') }}
            </h2>
            <a href="{{ route('jadwal.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi Pasien</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nama Pasien</p>
                            <p class="font-semibold">{{ $jadwal->pasien->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nomor Telepon</p>
                            <p class="font-semibold">{{ $jadwal->pasien->nomor_telepon }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Alamat</p>
                            <p class="font-semibold">{{ $jadwal->pasien->alamat }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Mulai Pengobatan</p>
                            <p class="font-semibold">{{ $jadwal->pasien->jadwal_pengobatan->format('d F Y') }}</p>
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
                            <p class="font-semibold">{{ $jadwal->tanggal_waktu_pengingat->format('d F Y - H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <p>
                                @if($jadwal->status === 'menunggu')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu
                                    </span>
                                @elseif($jadwal->status === 'terkirim')
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
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Fase</p>
                            <p>
                                @if($jadwal->fase === 'intensif')
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
                            <p class="font-semibold">{{ $jadwal->periode + 1 }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        @if($jadwal->status === 'menunggu')
                            <form method="POST" action="{{ route('jadwal.status', $jadwal) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="terkirim">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                                    Tandai Terkirim
                                </button>
                            </form>
                            <button 
                                type="button" 
                                class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded"
                                onclick="document.getElementById('reschedule-form').classList.toggle('hidden')">
                                Jadwal Ulang
                            </button>
                        @elseif($jadwal->status === 'gagal')
                            <button 
                                type="button" 
                                class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded"
                                onclick="document.getElementById('reschedule-form').classList.toggle('hidden')">
                                Jadwal Ulang
                            </button>
                        @endif
                    </div>

                    <div id="reschedule-form" class="mt-4 hidden">
                        <form method="POST" action="{{ route('jadwal.reschedule', $jadwal) }}" class="bg-gray-50 p-4 rounded-lg">
                            @csrf
                            @method('PATCH')
                            <h4 class="font-semibold mb-3">Atur Jadwal Ulang</h4>
                            <div class="mb-4">
                                <label for="tanggal_waktu_pengingat" class="block text-gray-700 text-sm font-bold mb-2">
                                    Tanggal dan Waktu Baru
                                </label>
                                <input 
                                    type="datetime-local" 
                                    name="tanggal_waktu_pengingat" 
                                    id="tanggal_waktu_pengingat"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                            </div>
                            <div class="flex items-center">
                                <button 
                                    type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Simpan Jadwal Baru
                                </button>
                                <button 
                                    type="button" 
                                    class="ml-4 text-gray-500 hover:text-gray-700"
                                    onclick="document.getElementById('reschedule-form').classList.add('hidden')">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if($jadwal->riwayat->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Riwayat Pengiriman</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jadwal->riwayat as $riwayat)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $riwayat->waktu_pengiriman->format('d M Y H:i:s') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($riwayat->status === 'terkirim')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Terkirim
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Gagal
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('riwayat.show', $riwayat) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 