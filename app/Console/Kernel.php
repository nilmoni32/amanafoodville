<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DailyIngredient::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   
        //for database backup scheduler
        $schedule->command('backup:clean')->dailyAt('0:00');
        $schedule->command('backup:run --only-db')->dailyAt('14:00');
        $schedule->command('backup:run --only-db')->dailyAt('18:00');
        $schedule->command('backup:run --only-db')->dailyAt('23:00');         
        // for sitemap
        $schedule->command('sitemap:generate')->dailyAt('2:00'); 
        //for ingredient scheduler
        $schedule->command('daily:ingredientUpdate')->dailyAt('5:00');
        //for reference discount
        $schedule->command('KOT:refdiscount')->dailyAt('6:00');
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
