<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Menjalankan perintah SendReminders setiap menit untuk memeriksa pengingat yang jatuh tempo
        $schedule->command('app:send-reminders')->everyMinute();
        
        // Jalankan pengiriman pengingat kontrol setiap jam
        $schedule->command('app:kirim-pengingat')
                ->hourly()
                ->appendOutputTo(storage_path('logs/pengingat.log'));
                
        // Jalankan pengiriman pengingat obat harian setiap 5 menit
        $schedule->command('pengingat:kirim-obat')
                ->everyFiveMinutes()
                ->appendOutputTo(storage_path('logs/pengingat-obat.log'));
                
        // Bersihkan jadwal obat lama dan tandai konfirmasi terlambat setiap hari jam 1 pagi
        $schedule->command('pengingat:bersihkan-obat')
                ->dailyAt('01:00')
                ->appendOutputTo(storage_path('logs/pembersihan-obat.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
