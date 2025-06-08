<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule für automatische Job-Synchronisation von Airtable
Schedule::command('airtable:sync')->hourly();

// Schedule für Job-Alert E-Mails
Schedule::command('job-alerts:send', ['--frequency=daily'])->dailyAt('09:00');
Schedule::command('job-alerts:send', ['--frequency=weekly'])->weeklyOn(1, '09:00'); // Montags um 9:00
