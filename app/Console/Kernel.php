<?php

namespace App\Console;

use App\Models\Fundraising;
use App\Models\Subscribe;
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
        foreach (Subscribe::all() as $subscribe) {
            $schedule->command('subscribe:process ' . $subscribe->getId())->daily()->at($subscribe->getScheduledAt());
        }
        foreach (Fundraising::all() as $item) {
            /** @uses CacheFundraisingCommand::class */
            $schedule->command('fundraising:cache ' . $item->getId())->everyMinute();
            /** @uses ValidateDonatesCommand::class */
            $schedule->command('validate:donates '  . $item->getId())->everyMinute();
        }
        $schedule->command('subscribe:reminder')->weeklyOn(6, '19:00');
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
