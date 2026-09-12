<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled tasks
|--------------------------------------------------------------------------
| Close ended auctions every minute (assign winners / mark unsold).
| For this to run automatically, ONE cron entry must call the Laravel
| scheduler each minute:
|     * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
| On Windows/XAMPP, use Task Scheduler to run `php artisan schedule:run`
| every minute (or run `php artisan schedule:work` in a terminal while testing).
*/
Schedule::command('auctions:close')->everyMinute()->withoutOverlapping();
