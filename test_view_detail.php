<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test View Detail Pengingat Obat ===\n";

// Login sebagai user pertama untuk testing
$user = \App\Models\User::first();
if ($user) {
    auth()->login($user);
    echo "Logged in as: {$user->username}\n";
}

// Ambil jadwal obat pertama
$jadwal = \App\Models\Jadwal::where('jenis', 'obat')->with('pasien')->first();

if ($jadwal) {
    echo "Jadwal ditemukan: ID {$jadwal->id}\n";
    echo "Pasien: {$jadwal->pasien->nama}\n";
    
    try {
        // Test view
        $view = view('pengingat-obat.show', compact('jadwal'));
        echo "✓ View berhasil di-load tanpa error!\n";
        
        // Test render (parsial)
        $content = $view->render();
        if (strlen($content) > 1000) {
            echo "✓ View berhasil di-render (content length: " . strlen($content) . ")\n";
        } else {
            echo "⚠ View di-render tapi content terlalu pendek\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    }
} else {
    echo "Tidak ada jadwal obat ditemukan\n";
}
