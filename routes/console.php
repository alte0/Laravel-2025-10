<?php

/*use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');*/

Schedule::command('app:warming-up-cache-last-tasks')
    ->everyMinute()
    ->onOneServer();

Schedule::command('app:clear-cache-last-tasks')
    ->daily()
    ->onOneServer();
