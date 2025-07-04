<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pasien') }}
            </h2>            <div class="flex space-x-2">
                <a href="{{ route('pasien.edit', $pasien) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit Pasien
                </a>
                <a href="{{ route('pasien.jadwal.edit', $pasien) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Atur Jadwal Kontrol
                </a>
                <a href="{{ route('pengingat-obat.create', $pasien) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.586V5L8 4z"></path>
                    </svg>
                    Buat Pengingat Obat
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

            <!-- Informasi Pasien -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Informasi Pasien</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Nama</h4>
                                <p class="text-lg">{{ $pasien->nama }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Alamat</h4>
                                <p class="text-lg">{{ $pasien->alamat }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Nomor WhatsApp</h4>
                                <p class="text-lg">{{ $pasien->nomor_telepon }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Tanggal Mulai Pengobatan</h4>
                                <p class="text-lg">{{ $pasien->jadwal_pengobatan->format('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <a href="#fase-intensif" class="fase-tab whitespace-nowrap py-4 px-6 border-b-2 border-blue-500 font-medium text-sm text-blue-600 focus:outline-none">
                            Fase Intensif
                        </a>
                        <a href="#fase-lanjutan" class="fase-tab whitespace-nowrap py-4 px-6 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                            Fase Lanjutan
                        </a>
                        <a href="#riwayat-pengingat" class="fase-tab whitespace-nowrap py-4 px-6 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                            Riwayat Pengingat
                        </a>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6 tab-content" id="fase-intensif-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Jadwal Pengingat Fase Intensif</h3>
                    </div>
                    
                    @if($jadwalIntensif->isEmpty())
                        <div class="text-gray-500 text-center py-4">
                            Tidak ada jadwal pengingat untuk fase intensif.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jadwalIntensif as $jadwal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">Minggu ke-{{ $jadwal->periode + 1 }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $jadwal->tanggal_waktu_pengingat->format('d F Y, H:i') }}</div>
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
                                                <a href="#" class="reschedule-btn text-indigo-600 hover:text-indigo-900" data-jadwal-id="{{ $jadwal->id }}" data-tanggal="{{ $jadwal->tanggal_waktu_pengingat->format('Y-m-d\TH:i') }}">Jadwalkan Ulang</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="p-6 tab-content hidden" id="fase-lanjutan-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Jadwal Pengingat Fase Lanjutan</h3>
                    </div>
                    
                    @if($jadwalLanjutan->isEmpty())
                        <div class="text-gray-500 text-center py-4">
                            Tidak ada jadwal pengingat untuk fase lanjutan.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jadwalLanjutan as $jadwal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">Periode ke-{{ $jadwal->periode + 1 }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $jadwal->tanggal_waktu_pengingat->format('d F Y, H:i') }}</div>
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
                                                <a href="#" class="reschedule-btn text-indigo-600 hover:text-indigo-900" data-jadwal-id="{{ $jadwal->id }}" data-tanggal="{{ $jadwal->tanggal_waktu_pengingat->format('Y-m-d\TH:i') }}">Jadwalkan Ulang</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="p-6 tab-content hidden" id="riwayat-pengingat-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Riwayat Pengingat</h3>
                        <a href="{{ route('pasien.riwayat', $pasien) }}" class="text-blue-600 hover:text-blue-800">Lihat Semua</a>
                    </div>
                    
                    <div id="riwayat-loading" class="text-center py-4">
                        <svg class="animate-spin h-6 w-6 mx-auto text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-2 text-gray-500">Memuat riwayat pengingat...</p>
                    </div>
                    
                    <div id="riwayat-content" class="hidden">
                        <!-- Riwayat akan diisi dengan script AJAX -->
                    </div>
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
            // Tab Navigation
            const tabs = document.querySelectorAll('.fase-tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => {
                        t.classList.remove('border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });
                    
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Add active class to current tab
                    tab.classList.add('border-blue-500', 'text-blue-600');
                    tab.classList.remove('border-transparent', 'text-gray-500');
                    
                    // Show corresponding content
                    const target = tab.getAttribute('href').substring(1);
                    document.getElementById(target + '-content').classList.remove('hidden');
                    
                    // Load riwayat if tab is riwayat-pengingat
                    if (target === 'riwayat-pengingat') {
                        loadRiwayat();
                    }
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
            
            // Function to load riwayat
            function loadRiwayat() {
                const riwayatLoading = document.getElementById('riwayat-loading');
                const riwayatContent = document.getElementById('riwayat-content');
                
                riwayatLoading.classList.remove('hidden');
                riwayatContent.classList.add('hidden');
                
                fetch(`{{ route('pasien.riwayat', $pasien) }}?format=json`)
                    .then(response => response.json())
                    .then(data => {
                        riwayatLoading.classList.add('hidden');
                        riwayatContent.classList.remove('hidden');
                        
                        let html = '';
                        if (data.length === 0) {
                            html = '<div class="text-gray-500 text-center py-4">Belum ada riwayat pengingat.</div>';
                        } else {
                            html = '<div class="overflow-x-auto">';
                            html += '<table class="min-w-full divide-y divide-gray-200">';
                            html += '<thead class="bg-gray-50">';
                            html += '<tr>';
                            html += '<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>';
                            html += '<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fase</th>';
                            html += '<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>';
                            html += '<th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>';
                            html += '</tr>';
                            html += '</thead>';
                            html += '<tbody class="bg-white divide-y divide-gray-200">';
                            
                            data.forEach(riwayat => {
                                html += '<tr>';
                                html += `<td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">${riwayat.waktu_pengiriman}</div></td>`;
                                html += `<td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">${riwayat.jadwal.fase}</div></td>`;
                                html += '<td class="px-6 py-4 whitespace-nowrap">';
                                
                                if (riwayat.status === 'terkirim') {
                                    html += '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Terkirim</span>';
                                } else {
                                    html += '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Gagal</span>';
                                }
                                
                                html += '</td>';
                                html += `<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">`;
                                html += `<a href="${riwayat.detail_url}" class="text-blue-600 hover:text-blue-900">Detail</a>`;
                                html += '</td>';
                                html += '</tr>';
                            });
                            
                            html += '</tbody>';
                            html += '</table>';
                            html += '</div>';
                        }
                        
                        riwayatContent.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error loading riwayat:', error);
                        riwayatLoading.classList.add('hidden');
                        riwayatContent.classList.remove('hidden');
                        riwayatContent.innerHTML = '<div class="text-red-500 text-center py-4">Gagal memuat riwayat pengingat.</div>';
                    });
            }
        });
    </script>
    @endpush
</x-app-layout> 