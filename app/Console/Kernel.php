<?php

namespace App\Console;

use App\Models\Fundraising;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        foreach (Fundraising::all() as $item) {
            /** @uses CacheFundraisingCommand::class */
            $schedule->command('fundraising:cache ' . $item->getId())->everyMinute();
        }
         $schedule->command('validate:donates')->everyMinute();
         $schedule->command('fill:amount')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
