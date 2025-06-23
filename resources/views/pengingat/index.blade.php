<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengingat WhatsApp') }}
            </h2>
            
            <form action="{{ route('pengingat.kirim-semua') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Kirim Semua Pengingat') }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="mb-4 px-4 py-2 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                            {{ session('info') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-medium mb-4">Daftar Jadwal Jatuh Tempo</h3>

                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <form action="{{ route('pengingat.index') }}" method="GET" class="flex items-center">
                                <label for="per_page" class="mr-2 text-sm text-gray-600">Tampilkan:</label>
                                <select name="per_page" id="per_page" class="rounded border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">
                                    @foreach ([25, 50, 100] as $value)
                                        <option value="{{ $value }}" {{ $perPage == $value ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                <span class="ml-2 text-sm text-gray-600">data</span>
                            </form>
                        </div>
                        <div class="text-sm text-gray-600">
                            Total data: {{ $totalJadwal }}
                        </div>
                    </div>

                    @if ($jadwalJatuhTempo->isEmpty())
                        <p class="text-gray-500">Tidak ada jadwal pengingat yang jatuh tempo saat ini.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Telepon</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fase</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($jadwalJatuhTempo as $jadwal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->pasien->nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->pasien->nomor_telepon }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jadwal->fase === 'intensif' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ ucfirst($jadwal->fase) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->tanggal_waktu_pengingat ? $jadwal->tanggal_waktu_pengingat->format('d M Y') : 'Belum dijadwalkan' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $jadwal->status === 'terkirim' ? 'bg-green-100 text-green-800' : 
                                                       ($jadwal->status === 'gagal' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($jadwal->status ?: 'belum dikirim') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('pengingat.kirim', $jadwal->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                        Kirim Pengingat
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Menampilkan {{ $jadwalJatuhTempo->firstItem() ?? 0 }} - {{ $jadwalJatuhTempo->lastItem() ?? 0 }} dari {{ $totalJadwal }} data
                            </div>
                            <div class="flex">
                                @if($jadwalJatuhTempo->previousPageUrl())
                                    <a href="{{ $jadwalJatuhTempo->previousPageUrl() }}&per_page={{ $perPage }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-l hover:bg-gray-300">
                                        &laquo; Sebelumnya
                                    </a>
                                @else
                                    <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-l">
                                        &laquo; Sebelumnya
                                    </span>
                                @endif
                                
                                @if($jadwalJatuhTempo->nextPageUrl())
                                    <a href="{{ $jadwalJatuhTempo->nextPageUrl() }}&per_page={{ $perPage }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-r hover:bg-gray-300 ml-1">
                                        Selanjutnya &raquo;
                                    </a>
                                @else
                                    <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-r ml-1">
                                        Selanjutnya &raquo;
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 