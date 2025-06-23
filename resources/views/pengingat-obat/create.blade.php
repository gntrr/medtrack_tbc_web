<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-2">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('pasien.index') }}" class="hover:text-gray-700">Pasien</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('pasien.show', $pasien) }}" class="hover:text-gray-700">{{ $pasien->nama }}</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span>Buat Pengingat Obat</span>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Pengingat Obat Harian') }}
            </h2>
            <p class="text-sm text-gray-600">Atur jadwal pengingat minum obat harian untuk {{ $pasien->nama }}</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Informasi Pasien</h2>
        </div>
        
        <div class="px-6 py-4 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nama Pasien</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $pasien->nama }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $pasien->nomor_telepon }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $pasien->alamat }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $pasien->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($pasien->status) }}
                        </span>
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Pengaturan Pengingat Obat</h2>
        </div>
        
        <form action="{{ route('pengingat-obat.store', $pasien) }}" method="POST" class="px-6 py-4">
            @csrf
            
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
                    <p class="mt-1 text-xs text-gray-500">Waktu pengiriman pengingat setiap hari</p>
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
                    <p class="mt-1 text-xs text-gray-500">Tanggal mulai pengingat obat</p>
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
                    <p class="mt-1 text-xs text-gray-500">Berapa hari pengingat akan dikirim (maksimal 365 hari)</p>
                </div>
            </div>

            <!-- Preview -->
            <div class="mt-6 p-4 bg-blue-50 rounded-md" id="preview-section" style="display: none;">
                <h3 class="text-sm font-medium text-blue-900">Preview Jadwal</h3>
                <div class="mt-2 text-sm text-blue-700" id="preview-content">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('pasien.show', $pasien) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Batal
                </a>
                
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Buat Pengingat Obat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const waktuInput = document.getElementById('waktu_pengingat');
    const tanggalInput = document.getElementById('tanggal_mulai');
    const durasiInput = document.getElementById('durasi_hari');
    const previewSection = document.getElementById('preview-section');
    const previewContent = document.getElementById('preview-content');

    function updatePreview() {
        const waktu = waktuInput.value;
        const tanggalMulai = tanggalInput.value;
        const durasi = parseInt(durasiInput.value);

        if (waktu && tanggalMulai && durasi && durasi > 0) {
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

            previewContent.innerHTML = `
                <div class="space-y-1">
                    <p><strong>Mulai:</strong> ${formatDate(startDate)} pukul ${waktu}</p>
                    <p><strong>Berakhir:</strong> ${formatDate(endDate)} pukul ${waktu}</p>
                    <p><strong>Total pengingat:</strong> ${durasi} hari</p>
                </div>
            `;
            previewSection.style.display = 'block';
        } else {
            previewSection.style.display = 'none';
        }
    }    waktuInput.addEventListener('change', updatePreview);
    tanggalInput.addEventListener('change', updatePreview);
    durasiInput.addEventListener('input', updatePreview);

    // Initial preview
    updatePreview();
});
</script>
        </div>
    </div>
</x-app-layout>
