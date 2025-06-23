<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Ringkasan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="text-blue-800 text-3xl font-bold">{{ $totalPasien ?? 0 }}</div>
                            <div class="text-blue-600">Total Pasien</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="text-green-800 text-3xl font-bold">{{ $jadwalHariIni->count() ?? 0 }}</div>
                            <div class="text-green-600">Pengingat Hari Ini</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                            <div class="text-purple-800 text-3xl font-bold">{{ $jadwalBelumKirim ?? 0 }}</div>
                            <div class="text-purple-600">Menunggu Dikirim</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Pengingat Hari Ini -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Jadwal Pengingat Hari Ini</h3>
                        <a href="{{ route('jadwal.index') }}" class="text-blue-600 hover:text-blue-800">Lihat Semua</a>
                    </div>
                    
                    @if($jadwalHariIni->isEmpty())
                        <div class="text-gray-500 text-center py-4">
                            Tidak ada jadwal pengingat untuk hari ini.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fase</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jadwalHariIni as $jadwal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $jadwal->pasien->nama }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($jadwal->pasien->alamat, 30) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $jadwal->tanggal_waktu_pengingat->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jadwal->fase === 'intensif' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ ucfirst($jadwal->fase) }}
                                                </span>
                                                <div class="text-xs text-gray-500">
                                                    Periode {{ $jadwal->periode + 1 }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($jadwal->status === 'menunggu')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Menunggu
                                                    </span>
                                                @elseif($jadwal->status === 'terkirim')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Terkirim
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Gagal
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('jadwal.show', $jadwal) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Jadwal Pengingat Mendatang -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Jadwal Pengingat Mendatang</h3>
                    </div>
                    
                    @if($jadwalMendatangByDate->isEmpty())
                        <div class="text-gray-500 text-center py-4">
                            Tidak ada jadwal pengingat untuk 7 hari ke depan.
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($jadwalMendatangByDate as $tanggal => $jadwalList)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                        <h4 class="font-medium">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</h4>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fase</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($jadwalList as $jadwal)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $jadwal->pasien->nama }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">
                                                                {{ $jadwal->tanggal_waktu_pengingat->format('H:i') }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jadwal->fase === 'intensif' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                                {{ ucfirst($jadwal->fase) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <a href="{{ route('jadwal.show', $jadwal) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
