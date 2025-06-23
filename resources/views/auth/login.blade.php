<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Sistem Pengingat Obat TBC</h1>
        <p class="text-sm text-gray-600 mt-1">Masuk untuk mengelola jadwal pengobatan pasien</p>
    </div>
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Nama Pengguna')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="Masukkan nama pengguna" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Kata Sandi')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            placeholder="Masukkan kata sandi"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('password.request') }}">
                    {{ __('Lupa kata sandi?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-blue-500 hover:bg-blue-700">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                Aplikasi ini digunakan untuk mengelola jadwal pengobatan dan pengiriman pengingat obat TBC
                <br>kepada pasien melalui WhatsApp.
            </p>
        </div>
    </form>
</x-guest-layout>
