<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk koneksi ke WhatsApp API
    |
    */

    // API Key untuk otentikasi ke layanan WhatsApp API
    'api_key' => env('WHATSAPP_API_KEY', ''),
    
    // URL API untuk WhatsApp Gateway
    'api_url' => env('WHATSAPP_API_URL', 'https://api.fonnte.com/send'),
    
    // Nomor pengirim (nomor WhatsApp yang terdaftar)
    'sender_number' => env('WHATSAPP_SENDER_NUMBER', ''),
]; 