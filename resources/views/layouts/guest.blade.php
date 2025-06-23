<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Pengingat Obat TBC') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-blue-50 to-white">
            <div class="mb-4">
                <a href="/" class="flex flex-col items-center">
                    <x-application-logo class="w-24 h-24 fill-blue-600" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-lg border border-gray-200">
                {{ $slot }}
            </div>
            
            <div class="mt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Sistem Pengingat Obat TBC | Dinas Kesehatan
            </div>
        </div>
    </body>
</html>
