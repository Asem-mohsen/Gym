<?php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\UpdateExpiredSubscriptionsJob;
use App\Jobs\UpdateOfferStatusJob;


// Schedule the expired subscriptions update job to run every 2 hours
Schedule::job(new UpdateExpiredSubscriptionsJob())->everyTwoHours();

// Schedule the offer status update job to run every 5 minutes
Schedule::job(new UpdateOfferStatusJob())->everyFiveMinutes();

// Schedule queue:work to run every minute for Horizon cron jobs
Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
