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
        foreach (Fundraising::query()->withTrashed()->get()->all() as $item) {
            /** @uses FundraisingCacheCommand::class */
            $schedule->command('fundraising:cache ' . $item->getId())->everyFiveMinutes();
            /** @uses DonatesValidateCommand::class */
            $schedule->command('donates:validate '  . $item->getId())->everyFiveMinutes();
        }
        $schedule->command('subscribe:reminder')->weeklyOn(7, '14:00');
        $schedule->command('subscribe:scheduler')->everySecond();
        $schedule->command('fundraising:forget-links')->everySecond();
        $schedule->command('fundraising:deactivate')->dailyAt('09:00');
        $schedule->command('fundraising:remove')->dailyAt('23:59');
        $schedule->command('fundraising:activate')->everyMinute();
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
