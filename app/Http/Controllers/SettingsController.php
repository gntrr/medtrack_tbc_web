<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $whatsappSettings = Setting::where('group', 'whatsapp')->get();
        $generalSettings = Setting::where('group', 'general')->get();
        
        return view('settings.index', compact('whatsappSettings', 'generalSettings'));
    }

    /**
     * Update WhatsApp settings
     */
    public function updateWhatsApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'whatsapp_api_key' => 'required|string|min:10',
            'whatsapp_api_url' => 'required|url',
            'whatsapp_sender_number' => 'required|string|regex:/^628[0-9]{8,12}$/',
        ], [
            'whatsapp_api_key.required' => 'API Key WhatsApp harus diisi',
            'whatsapp_api_key.min' => 'API Key WhatsApp minimal 10 karakter',
            'whatsapp_api_url.required' => 'URL API WhatsApp harus diisi',
            'whatsapp_api_url.url' => 'URL API WhatsApp harus berformat URL yang valid',
            'whatsapp_sender_number.required' => 'Nomor WhatsApp pengirim harus diisi',
            'whatsapp_sender_number.regex' => 'Format nomor WhatsApp harus 628xxxxxxxxxx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update WhatsApp settings
        Setting::set('whatsapp_api_key', $request->whatsapp_api_key, 'string', 'whatsapp', 'WhatsApp API Key', 'API Key dari Fonnte untuk integrasi WhatsApp', true);
        Setting::set('whatsapp_api_url', $request->whatsapp_api_url, 'string', 'whatsapp', 'WhatsApp API URL', 'URL endpoint untuk API WhatsApp Fonnte', false);
        Setting::set('whatsapp_sender_number', $request->whatsapp_sender_number, 'string', 'whatsapp', 'Nomor WhatsApp Pengirim', 'Nomor WhatsApp yang terdaftar di Fonnte', false);

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan WhatsApp berhasil diperbarui');
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'reminder_hours' => 'required|integer|min:1|max:72',
            'auto_cleanup_days' => 'required|integer|min:1|max:365',
        ], [
            'app_name.required' => 'Nama aplikasi harus diisi',
            'reminder_hours.required' => 'Batas waktu konfirmasi harus diisi',
            'reminder_hours.integer' => 'Batas waktu konfirmasi harus berupa angka',
            'reminder_hours.min' => 'Batas waktu konfirmasi minimal 1 jam',
            'reminder_hours.max' => 'Batas waktu konfirmasi maksimal 72 jam',
            'auto_cleanup_days.required' => 'Auto cleanup harus diisi',
            'auto_cleanup_days.integer' => 'Auto cleanup harus berupa angka',
            'auto_cleanup_days.min' => 'Auto cleanup minimal 1 hari',
            'auto_cleanup_days.max' => 'Auto cleanup maksimal 365 hari',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update general settings
        Setting::set('app_name', $request->app_name, 'string', 'general', 'Nama Aplikasi', 'Nama aplikasi yang ditampilkan');
        Setting::set('reminder_hours', $request->reminder_hours, 'integer', 'general', 'Batas Waktu Konfirmasi (Jam)', 'Berapa jam pasien bisa konfirmasi setelah pengingat dikirim');
        Setting::set('auto_cleanup_days', $request->auto_cleanup_days, 'integer', 'general', 'Auto Cleanup (Hari)', 'Berapa hari data lama akan dibersihkan otomatis');

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan umum berhasil diperbarui');
    }

    /**
     * Test WhatsApp connection
     */
    public function testWhatsApp()
    {
        $apiKey = Setting::get('whatsapp_api_key');
        $apiUrl = Setting::get('whatsapp_api_url');
        $senderNumber = Setting::get('whatsapp_sender_number');

        if (empty($apiKey) || empty($apiUrl) || empty($senderNumber)) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaturan WhatsApp belum lengkap. Pastikan API Key, URL, dan Nomor WhatsApp sudah diisi.'
            ]);
        }

        try {
            // Test dengan nomor pengirim sendiri
            $response = app(\App\Services\WhatsAppService::class)->kirimPesan(
                $senderNumber,
                'Test koneksi WhatsApp API dari MedTrack - ' . now()->format('Y-m-d H:i:s')
            );

            return response()->json([
                'success' => $response['success'],
                'message' => $response['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Clear all settings cache
     */
    public function clearCache()
    {
        Setting::clearCache();
        
        return redirect()->route('settings.index')
            ->with('success', 'Cache pengaturan berhasil dibersihkan');
    }
}
