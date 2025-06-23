<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal Pengingat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Jadwal Pengingat Hari Ini -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Pengingat Hari Ini ({{ now()->format('d M Y') }})</h3>
                    
                    @if($jadwalHariIni->isEmpty())
                        <div class="text-gray-500 text-center py-4">
                            Tidak ada jadwal pengingat untuk hari ini.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fase</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jadwalHariIni as $jadwal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $jadwal->pasien->nama }}</div>
                                                <div class="text-xs text-gray-500">{{ $jadwal->pasien->nomor_telepon }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $jadwal->tanggal_waktu_pengingat->format('H:i') }}</div>
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
                                                <form method="POST" action="{{ route('jadwal.status', $jadwal) }}" class="status-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="status-select text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $jadwal->status === 'menunggu' ? 'bg-yellow-50' : ($jadwal->status === 'terkirim' ? 'bg-green-50' : 'bg-red-50') }}">
                                                        <option value="menunggu" {{ $jadwal->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                        <option value="terkirim" {{ $jadwal->status === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                                                        <option value="gagal" {{ $jadwal->status === 'gagal' ? 'selected' : '' }}>Gagal</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('jadwal.show', $jadwal) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                <a href="#" class="reschedule-btn text-indigo-600 hover:text-indigo-900" data-jadwal-id="{{ $jadwal->id }}" data-tanggal="{{ $jadwal->tanggal_waktu_pengingat->format('Y-m-d\TH:i') }}">Jadwal Ulang</a>
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
                    <h3 class="text-lg font-semibold mb-4">Pengingat Mendatang (7 Hari Ke Depan)</h3>
                    
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
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fase</th>
                                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($jadwalList as $jadwal)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $jadwal->pasien->nama }}</div>
                                                            <div class="text-xs text-gray-500">{{ $jadwal->pasien->nomor_telepon }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ $jadwal->tanggal_waktu_pengingat->format('H:i') }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jadwal->fase === 'intensif' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                                {{ ucfirst($jadwal->fase) }}
                                                            </span>
                                                            <div class="text-xs text-gray-500">
                                                                Periode {{ $jadwal->periode + 1 }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <a href="{{ route('jadwal.show', $jadwal) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                            <a href="#" class="reschedule-btn text-indigo-600 hover:text-indigo-900" data-jadwal-id="{{ $jadwal->id }}" data-tanggal="{{ $jadwal->tanggal_waktu_pengingat->format('Y-m-d\TH:i') }}">Jadwal Ulang</a>
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
    
    <!-- Modal Reschedule -->
    <div id="reschedule-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="reschedule-form" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Jadwalkan Ulang Pengingat
                                </h3>
                                <div class="mt-4">
                                    <label for="tanggal_waktu_pengingat" class="block text-sm font-medium text-gray-700">Tanggal & Waktu Baru</label>
                                    <input type="datetime-local" name="tanggal_waktu_pengingat" id="tanggal_waktu_pengingat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm close-modal">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status select change handler
            const statusSelects = document.querySelectorAll('.status-select');
            
            statusSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
            
            // Reschedule Modal
            const rescheduleButtons = document.querySelectorAll('.reschedule-btn');
            const modal = document.getElementById('reschedule-modal');
            const closeButtons = document.querySelectorAll('.close-modal');
            const rescheduleForm = document.getElementById('reschedule-form');
            const tanggalInput = document.getElementById('tanggal_waktu_pengingat');
            
            rescheduleButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const jadwalId = button.getAttribute('data-jadwal-id');
                    const tanggal = button.getAttribute('data-tanggal');
                    
                    rescheduleForm.action = `{{ route('jadwal.reschedule', '') }}/${jadwalId}`;
                    tanggalInput.value = tanggal;
                    
                    modal.classList.remove('hidden');
                });
            });
            
            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 