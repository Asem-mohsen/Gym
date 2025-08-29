<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\UpdateExpiredSubscriptionsJob;


// Schedule the expired subscriptions update job to run every 2 hours
Schedule::job(new UpdateExpiredSubscriptionsJob())->everyTwoHours();
