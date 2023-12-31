<?php

namespace App\Console;

use Domain\Estate\Actions\AskEstateRelevanceAction;
use Domain\Estate\Actions\CloseExpiredEstatesAction;
use Domain\Estate\Actions\CloseIrrelevantEstatesAction;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(CloseIrrelevantEstatesAction::class)->daily()
            ->appendOutputTo(storage_path('logs/scheduler.log'));
        $schedule->call(AskEstateRelevanceAction::class)->daily()
            ->appendOutputTo(storage_path('logs/scheduler.log'));
        $schedule->call(CloseExpiredEstatesAction::class)->daily()
            ->appendOutputTo(storage_path('logs/scheduler.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
