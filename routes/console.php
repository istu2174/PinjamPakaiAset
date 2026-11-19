<?php

use App\Console\Commands\KirimReminderCommand;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes (Scheduled Commands)
|--------------------------------------------------------------------------
| Reminder otomatis dikirim setiap hari pukul 08.00 WIB.
| Pastikan Laravel Scheduler aktif:
|   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
*/

Schedule::command(KirimReminderCommand::class)
    ->dailyAt('08:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping()
    ->runInBackground();
