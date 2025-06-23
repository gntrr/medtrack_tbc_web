<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Pengingat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filter dan Pencarian -->
                    <div class="mb-6">
                        <form action="{{ route('riwayat.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="pasien" class="block text-sm font-medium text-gray-700 mb-1">Pasien</label>
                                <select name="pasien" id="pasien" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Pasien</option>
                                    @foreach($pasienList as $p)
                                        <option value="{{ $p->id }}" {{ request('pasien') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Status</option>
                                    <option value="terkirim" {{ request('status') == 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                                    <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                </select>
                            </div>
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ request('tanggal') }}">
                            </div>
                            <div class="md:col-span-3 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                    </svg>
                                    Filter
                                </button>
                                @if(request('pasien') || request('status') || request('tanggal'))
                                    <a href="{{ route('riwayat.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Riwayat Table -->
                    @if($riwayat->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mt-2">Tidak ada data riwayat pengingat</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pengiriman</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fase</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($riwayat as $r)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $r->waktu_pengiriman->format('d M Y, H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $r->jadwal->pasien->nama }}</div>
                                                <div class="text-xs text-gray-500">{{ $r->jadwal->pasien->nomor_telepon }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $r->jadwal->fase === 'intensif' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ ucfirst($r->jadwal->fase) }}
                                                </span>
                                                <div class="text-xs text-gray-500">
                                                    Periode {{ $r->jadwal->periode + 1 }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($r->status === 'terkirim')
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
                                                <a href="{{ route('riwayat.show', $r) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $riwayat->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 