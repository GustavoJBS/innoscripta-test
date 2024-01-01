<?php

namespace App\Console;

use App\Console\Commands\SyncArticlesFromNewsApis;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SyncArticlesFromNewsApis::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:sync-source-articles-from-apis')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
