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
        \App\Console\Commands\WeeklyTaskReport::class,
        \App\Console\Commands\TodayTaskRemind::class,
        \App\Console\Commands\LeadFollowupReminder::class,
        \App\Console\Commands\WeeklyBusinessReport::class,
        \App\Console\Commands\BackupDatabase::class,
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\SettingAllCommand::class,
        \App\Console\Commands\SettingGetCommand::class,
        \App\Console\Commands\SettingSetCommand::class,
        \App\Console\Commands\SettingLoadCommand::class,
        \App\Console\Commands\SettingClearCommand::class,
        \App\Console\Commands\SettingForgetCommand::class,
        \App\Console\Commands\EmiDueNotificationCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:emiDuesNotification')->daily();
        $schedule->command('tasks:week')->daily();
        $schedule->command('tasks:today')->daily();
        $schedule->command('leads:followup')->daily();
        $schedule->command('business:week')->hourly();
        $schedule->command('db:backup')->mondays()->at('23:00');

        // exec method
        $host = config('database.connections.mysql.host');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');

        $schedule->exec("mysqldump -h {$host} -u {$username} -p{$password} {$database}")
              ->daily()->sendOutputTo('/backups/daily_backup.sql');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
