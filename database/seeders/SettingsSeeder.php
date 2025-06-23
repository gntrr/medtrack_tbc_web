<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // WhatsApp API Settings
            [
                'key' => 'whatsapp_api_key',
                'value' => 'your_fonnte_api_key_here',
                'type' => 'string',
                'group' => 'whatsapp',
                'label' => 'WhatsApp API Key',
                'description' => 'API Key dari Fonnte untuk integrasi WhatsApp',
                'is_encrypted' => false // Don't encrypt dummy value
            ],
            [
                'key' => 'whatsapp_api_url',
                'value' => 'https://api.fonnte.com/send',
                'type' => 'string',
                'group' => 'whatsapp',
                'label' => 'WhatsApp API URL',
                'description' => 'URL endpoint untuk API WhatsApp Fonnte',
                'is_encrypted' => false
            ],
            [
                'key' => 'whatsapp_sender_number',
                'value' => 'your_whatsapp_number_here',
                'type' => 'string',
                'group' => 'whatsapp',
                'label' => 'Nomor WhatsApp Pengirim',
                'description' => 'Nomor WhatsApp yang terdaftar di Fonnte (format: 628xxxxxxxxxx)',
                'is_encrypted' => false
            ],
            
            // General App Settings
            [
                'key' => 'app_name',
                'value' => 'MedTrack',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nama Aplikasi',
                'description' => 'Nama aplikasi yang ditampilkan',
                'is_encrypted' => false
            ],
            [
                'key' => 'reminder_hours',
                'value' => '12',
                'type' => 'integer',
                'group' => 'general',
                'label' => 'Batas Waktu Konfirmasi (Jam)',
                'description' => 'Berapa jam pasien bisa konfirmasi setelah pengingat dikirim',
                'is_encrypted' => false
            ],
            [
                'key' => 'auto_cleanup_days',
                'value' => '30',
                'type' => 'integer',
                'group' => 'general',
                'label' => 'Auto Cleanup (Hari)',
                'description' => 'Berapa hari data lama akan dibersihkan otomatis',
                'is_encrypted' => false
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Default settings berhasil dibuat/diperbarui.');
    }
}
