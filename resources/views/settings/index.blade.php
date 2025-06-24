<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Pengaturan Sistem') }}
                </h2>
                <p class="text-sm text-gray-600">Kelola konfigurasi aplikasi dan integrasi WhatsApp</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="testWhatsApp()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Test WhatsApp
                </button>
                <a href="{{ route('settings.clear-cache') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Clear Cache
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- WhatsApp Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            Pengaturan WhatsApp API
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Konfigurasi integrasi dengan API Fonnte</p>
                    </div>
                    
                    <form action="{{ route('settings.update-whatsapp') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="whatsapp_api_key" class="block text-sm font-medium text-gray-700">API Key</label>
                            <input type="password" 
                                   name="whatsapp_api_key" 
                                   id="whatsapp_api_key"
                                   value="{{ old('whatsapp_api_key', $whatsappSettings->where('key', 'whatsapp_api_key')->first()->value ?? '') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan API Key dari Fonnte">
                            @error('whatsapp_api_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Dapatkan API Key dari <a href="https://fonnte.com" target="_blank" class="text-blue-600 hover:underline">fonnte.com</a>
                            </p>
                        </div>

                        <div>
                            <label for="whatsapp_api_url" class="block text-sm font-medium text-gray-700">API URL</label>
                            <input type="url" 
                                   name="whatsapp_api_url" 
                                   id="whatsapp_api_url"
                                   value="{{ old('whatsapp_api_url', $whatsappSettings->where('key', 'whatsapp_api_url')->first()->value ?? 'https://api.fonnte.com/send') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://api.fonnte.com/send">
                            @error('whatsapp_api_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="whatsapp_sender_number" class="block text-sm font-medium text-gray-700">Nomor WhatsApp Pengirim</label>
                            <input type="text" 
                                   name="whatsapp_sender_number" 
                                   id="whatsapp_sender_number"
                                   value="{{ old('whatsapp_sender_number', $whatsappSettings->where('key', 'whatsapp_sender_number')->first()->value ?? '') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="628xxxxxxxxxx">
                            @error('whatsapp_sender_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Format: 628xxxxxxxxxx (nomor yang terdaftar di Fonnte)
                            </p>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Pengaturan WhatsApp
                            </button>
                        </div>
                    </form>
                </div>

                <!-- General Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Pengaturan Umum
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Konfigurasi aplikasi dan behavior sistem</p>
                    </div>
                    
                    <form action="{{ route('settings.update-general') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700">Nama Aplikasi</label>
                            <input type="text" 
                                   name="app_name" 
                                   id="app_name"
                                   value="{{ old('app_name', $generalSettings->where('key', 'app_name')->first()->value ?? 'MedTrack') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="MedTrack">
                            @error('app_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reminder_hours" class="block text-sm font-medium text-gray-700">Batas Waktu Konfirmasi (Jam)</label>
                            <input type="number" 
                                   name="reminder_hours" 
                                   id="reminder_hours"
                                   min="1" max="72"
                                   value="{{ old('reminder_hours', $generalSettings->where('key', 'reminder_hours')->first()->value ?? 12) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('reminder_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Berapa jam pasien bisa konfirmasi setelah pengingat dikirim
                            </p>
                        </div>

                        <div>
                            <label for="auto_cleanup_days" class="block text-sm font-medium text-gray-700">Auto Cleanup (Hari)</label>
                            <input type="number" 
                                   name="auto_cleanup_days" 
                                   id="auto_cleanup_days"
                                   min="1" max="365"
                                   value="{{ old('auto_cleanup_days', $generalSettings->where('key', 'auto_cleanup_days')->first()->value ?? 30) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('auto_cleanup_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Data lebih lama dari ini akan dibersihkan otomatis
                            </p>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Pengaturan Umum
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Section -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Informasi Penting:</strong><br>
                            • Pengaturan key otomatis terganti ke pengaturan default jika ada deployment baru<br>
                            • Gunakan tombol "Test WhatsApp" untuk memverifikasi koneksi<br>
                            • Clear cache jika pengaturan tidak berubah setelah disimpan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function testWhatsApp() {
        const button = event.target;
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Testing...';
        
        fetch('{{ route("settings.test-whatsapp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Test WhatsApp berhasil!\n\n' + data.message);
            } else {
                alert('❌ Test WhatsApp gagal!\n\n' + data.message);
            }
        })
        .catch(error => {
            alert('❌ Error: ' + error.message);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
    </script>
</x-app-layout>
