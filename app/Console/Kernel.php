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
        // COMMAND UTAMA - Handle semua jenis pengingat (kontrol + obat)
        $schedule->command('app:send-reminders')
                ->everyMinute()
                ->appendOutputTo(storage_path('logs/pengingat.log'))
                ->withoutOverlapping()
                ->runInBackground();
                
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
