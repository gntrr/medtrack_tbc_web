<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Pengingat Obat Pasien') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="relative min-h-screen">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-medium text-indigo-600 hover:text-indigo-800 focus:outline-none focus:underline transition ease-in-out duration-150">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-800 focus:outline-none focus:underline transition ease-in-out duration-150">Log in</a>
                    @endauth
                </div>
            @endif

            <div class="py-12 sm:py-24">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block">Sistem Pengingat</span>
                            <span class="block text-indigo-600">Pengobatan TB</span>
                        </h1>
                        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                            Membantu petugas kesehatan untuk mengingatkan pasien TB melalui WhatsApp secara otomatis sesuai jadwal pengobatan.
                        </p>
                    </div>
                </div>
                                </div>

            <div class="pb-12 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:text-center">
                        <p class="mt-2 pt-6 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Fitur Utama
                                </p>
                            </div>

                    <div class="mt-10">
                        <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-16">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Manajemen Pasien</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Kelola data pasien TB dengan mudah termasuk informasi kontak dan riwayat pengobatan.
                                    </p>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-16">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Jadwal Otomatis</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Pengaturan jadwal pengingat fase intensif dan lanjutan secara otomatis berdasarkan tanggal mulai pengobatan.
                                    </p>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                    </svg>
                                </div>
                                <div class="ml-16">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Pengingat WhatsApp</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Kirim pengingat otomatis melalui WhatsApp tepat waktu untuk meningkatkan kepatuhan pengobatan pasien.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>

            <div class="bg-indigo-700">
                <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                        <span class="block">Siap memulai?</span>
                    </h2>
                    <p class="mt-4 text-lg leading-6 text-indigo-200">
                        Masuk untuk mulai mengelola pasien dan jadwal pengingat.
                    </p>
                    <a href="{{ route('login') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 sm:w-auto">
                        Masuk ke Sistem
                    </a>
                </div>
            </div>

            <footer class="bg-white">
                <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
                    <p class="mt-8 text-center text-base text-gray-400">
                        &copy; {{ date('Y') }} Sistem Pengingat Pengobatan TB. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
