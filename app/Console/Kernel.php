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
        Commands\AutoPresensi::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('auto:run')
                ->dailyAt('23:57')
                ->runInBackground()
                ->withoutOverlapping();

        // exec method
            $host = config('database.connections.mysql.host');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $database = config('database.connections.mysql.database');
            $today = today()->format('Y-m-d');

            $schedule->exec("mysqldump -h{$host} -u{$username} -p{$password} {$database}")
            // ->between('19:54', '20:30')
            ->dailyAt('23:57')
            ->onSuccess(function(){
                \Log::info('sukses');
            })
            ->onFailure(function(){
                \Log::error('gagal');
            })
            ->sendOutputTo(storage_path()."/backups/backup_{$today}.sql");
    }

    /**
     * Register the commands for the application.
     * Rahmat Suregar
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
