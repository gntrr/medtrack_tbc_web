@extends('layouts.app')

@section('title', 'Buat Pengingat Obat Massal')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('pengingat-obat.index') }}" class="hover:text-gray-700">Pengingat Obat</a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span>Buat Massal</span>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Buat Pengingat Obat Massal</h1>
        <p class="mt-1 text-sm text-gray-600">Buat jadwal pengingat obat untuk multiple pasien sekaligus</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('pengingat-obat.bulk-store') }}" method="POST">
            @csrf
            
            <!-- Pilih Pasien -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Pilih Pasien</h2>
                <p class="mt-1 text-sm text-gray-600">Pilih pasien yang akan dibuat jadwal pengingat obatnya</p>
            </div>
            
            <div class="px-6 py-4">
                <div class="mb-4">
                    <div class="flex items-center space-x-2 mb-3">
                        <input type="checkbox" 
                               id="select-all" 
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="select-all" class="text-sm font-medium text-gray-700">
                            Pilih Semua Pasien ({{ $pasienAktif->count() }} pasien)
                        </label>
                    </div>
                    
                    @if($pasienAktif->count() > 0)
                        <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-md">
                            @foreach($pasienAktif as $pasien)
                                <div class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                                    <input type="checkbox" 
                                           id="pasien-{{ $pasien->id }}" 
                                           name="pasien_ids[]" 
                                           value="{{ $pasien->id }}"
                                           class="pasien-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="pasien-{{ $pasien->id }}" class="ml-3 flex-1 cursor-pointer">
                                        <div class="text-sm font-medium text-gray-900">{{ $pasien->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $pasien->nomor_telepon }} • {{ $pasien->alamat }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pasien aktif</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada pasien dengan status aktif untuk dibuat jadwal pengingat obat.</p>
                        </div>
                    @endif
                    
                    @error('pasien_ids')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Pengaturan Jadwal -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Jadwal</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="waktu_pengingat" class="block text-sm font-medium text-gray-700">
                            Waktu Pengingat <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="waktu_pengingat" 
                               name="waktu_pengingat" 
                               value="{{ old('waktu_pengingat', '07:00') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('waktu_pengingat') border-red-300 @enderror">
                        @error('waktu_pengingat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Waktu pengiriman pengingat setiap hari untuk semua pasien</p>
                    </div>

                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal_mulai" 
                               name="tanggal_mulai" 
                               value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tanggal_mulai') border-red-300 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Tanggal mulai pengingat obat untuk semua pasien</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="durasi_hari" class="block text-sm font-medium text-gray-700">
                            Durasi Pengingat (Hari) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="durasi_hari" 
                               name="durasi_hari" 
                               value="{{ old('durasi_hari', '30') }}"
                               min="1" 
                               max="365"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('durasi_hari') border-red-300 @enderror">
                        @error('durasi_hari')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Berapa hari pengingat akan dikirim untuk setiap pasien (maksimal 365 hari)</p>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="px-6 py-4 bg-blue-50 border-t border-gray-200" id="summary-section" style="display: none;">
                <h3 class="text-sm font-medium text-blue-900">Ringkasan</h3>
                <div class="mt-2 text-sm text-blue-700" id="summary-content">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <a href="{{ route('pengingat-obat.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Batal
                </a>
                
                <button type="submit" 
                        id="submit-button"
                        disabled
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50 disabled:cursor-not-allowed">
                    Buat Pengingat Obat Massal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const pasienCheckboxes = document.querySelectorAll('.pasien-checkbox');
    const waktuInput = document.getElementById('waktu_pengingat');
    const tanggalInput = document.getElementById('tanggal_mulai');
    const durasiInput = document.getElementById('durasi_hari');
    const summarySection = document.getElementById('summary-section');
    const summaryContent = document.getElementById('summary-content');
    const submitButton = document.getElementById('submit-button');

    // Handle select all
    selectAllCheckbox.addEventListener('change', function() {
        pasienCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateSummary();
    });

    // Handle individual checkboxes
    pasienCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.pasien-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === pasienCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < pasienCheckboxes.length;
            updateSummary();
        });
    });

    // Handle form inputs
    waktuInput.addEventListener('change', updateSummary);
    tanggalInput.addEventListener('change', updateSummary);
    durasiInput.addEventListener('input', updateSummary);

    function updateSummary() {
        const selectedPasien = document.querySelectorAll('.pasien-checkbox:checked').length;
        const waktu = waktuInput.value;
        const tanggalMulai = tanggalInput.value;
        const durasi = parseInt(durasiInput.value);

        if (selectedPasien > 0 && waktu && tanggalMulai && durasi && durasi > 0) {
            const startDate = new Date(tanggalMulai);
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + durasi - 1);

            const formatDate = (date) => {
                return date.toLocaleDateString('id-ID', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            };

            const totalJadwal = selectedPasien * durasi;

            summaryContent.innerHTML = `
                <div class="space-y-1">
                    <p><strong>Pasien terpilih:</strong> ${selectedPasien} pasien</p>
                    <p><strong>Periode:</strong> ${formatDate(startDate)} - ${formatDate(endDate)}</p>
                    <p><strong>Waktu pengingat:</strong> ${waktu} WIB setiap hari</p>
                    <p><strong>Total jadwal:</strong> ${totalJadwal} pengingat</p>
                </div>
            `;
            summarySection.style.display = 'block';
            submitButton.disabled = false;
        } else {
            summarySection.style.display = 'none';
            submitButton.disabled = true;
        }
    }

    // Initial update
    updateSummary();
});
</script>
@endsection
