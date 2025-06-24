<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-2">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('pengingat-obat.index') }}" class="hover:text-gray-700">Pengingat Obat</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span>Detail Pengingat</span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Detail Pengingat Obat') }}
                    </h2>
                    <p class="text-sm text-gray-600">Informasi lengkap pengingat obat untuk {{ $jadwal->pasien->nama }}</p>
                </div>
                <div class="flex space-x-2">
                    @if($jadwal->status === 'menunggu')
                        <form action="{{ route('pengingat-obat.kirim-manual', $jadwal) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin mengirim pengingat WhatsApp sekarang?')"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Kirim WhatsApp
                            </button>
                        </form>

                        <button onclick="updateStatus('terkirim')" 
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Tandai Terkirim
                        </button>
                    @endif
                
                <button onclick="showRescheduleModal()" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Reschedule
                </button>
                
                <button onclick="confirmDelete()" 
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>                    Hapus
                </button>
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informasi Pasien -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Informasi Pasien</h2>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jadwal->pasien->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jadwal->pasien->nomor_telepon }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jadwal->pasien->alamat }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status Pasien</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $jadwal->pasien->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($jadwal->pasien->status) }}
                            </span>
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pengingat -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Informasi Pengingat</h2>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jenis Pengingat</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.586V5L8 4z"></path>
                                </svg>
                                Pengingat Obat Harian
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jadwal->tanggal_waktu_pengingat->format('d F Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status Pengiriman</dt>
                        <dd class="mt-1">
                            @if($jadwal->status === 'terkirim')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Terkirim
                                </span>
                            @elseif($jadwal->status === 'gagal')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Gagal
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Menunggu
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status Konfirmasi</dt>
                        <dd class="mt-1">
                            @if($jadwal->status_konfirmasi === 'sudah')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Sudah Konfirmasi
                                </span>
                            @elseif($jadwal->status_konfirmasi === 'terlambat')
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
                                    Menunggu Konfirmasi
                                </span>
                            @endif
                        </dd>
                    </div>
                    @if($jadwal->tgl_waktu_konfirmasi)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Waktu Konfirmasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $jadwal->tgl_waktu_konfirmasi->format('d F Y, H:i') }}</dd>
                        </div>
                    @endif                </div>
            </div>
        </div>
    </div>

    <!-- Link Konfirmasi -->
    @if($jadwal->token_konfirmasi)
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Link Konfirmasi</h2>
            </div>
            <div class="px-6 py-4">
                <div class="bg-gray-50 rounded-md p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL Konfirmasi</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" 
                               id="confirmation-link" 
                               value="{{ route('konfirmasi-obat.show', $jadwal->token_konfirmasi) }}" 
                               readonly 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-white text-sm">
                        <button onclick="copyToClipboard()" 
                                class="px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <a href="{{ route('konfirmasi-obat.show', $jadwal->token_konfirmasi) }}" 
                           target="_blank"
                           class="px-3 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Link ini dapat digunakan untuk konfirmasi minum obat. Link akan dikirim via WhatsApp ke pasien.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Riwayat -->
    @if($jadwal->riwayat->count() > 0)
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Riwayat Aktivitas</h2>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-3">
                    @foreach($jadwal->riwayat as $riwayat)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $riwayat->pesan ?? 'Pesan tidak tersedia' }}</p>
                                <p class="text-sm text-gray-500">{{ $riwayat->waktu_pengiriman ? $riwayat->waktu_pengiriman->format('d F Y, H:i') : 'Waktu tidak tersedia' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal Reschedule -->
<div id="reschedule-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Reschedule Pengingat</h3>
            </div>
            <form id="reschedule-form" method="POST" action="{{ route('pengingat-obat.reschedule', $jadwal) }}">
                @csrf
                @method('PUT')
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label for="new_datetime" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal & Waktu Baru
                        </label>
                        <input type="datetime-local" 
                               id="new_datetime" 
                               name="tanggal_waktu_pengingat" 
                               value="{{ $jadwal->tanggal_waktu_pengingat->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-2">
                    <button type="button" onclick="hideRescheduleModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>        </div>
    </div>
</div>

        </div>
    </div>

<script>
function updateStatus(status) {
    if (confirm('Apakah Anda yakin ingin mengubah status pengingat ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("pengingat-obat.update-status", $jadwal) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = status;
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(statusField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function showRescheduleModal() {
    document.getElementById('reschedule-modal').classList.remove('hidden');
}

function hideRescheduleModal() {
    document.getElementById('reschedule-modal').classList.add('hidden');
}

function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus pengingat obat ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("pengingat-obat.destroy", $jadwal) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function copyToClipboard() {
    const linkField = document.getElementById('confirmation-link');
    linkField.select();
    linkField.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show feedback
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
      setTimeout(() => {
        button.innerHTML = originalText;
    }, 2000);
}
</script>
        </div>
    </div>
</x-app-layout>
