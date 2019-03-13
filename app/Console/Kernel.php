<?php

namespace App\Console;
use App\Console\Commands\ActivateApiKey;
use App\Console\Commands\DeactivateApiKey;
use App\Console\Commands\DeleteApiKey;
use App\Console\Commands\GenerateApiKey;
use App\Console\Commands\ListApiKeys;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ActivateApiKey::class,
        DeactivateApiKey::class,
        DeleteApiKey::class,
        GenerateApiKey::class,
        ListApiKeys::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
