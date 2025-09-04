<?php

namespace App\Jobs;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateOfferStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $offers = Offer::all();

            foreach ($offers as $offer) {
                $shouldBeActive = $offer->isActive();
                $currentStatus = $offer->status;

                // Update status based on date logic
                if ($shouldBeActive && $currentStatus == 0) {
                    // Activate offer
                    $offer->update(['status' => 1]);
                } elseif (!$shouldBeActive && $currentStatus == 1) {
                    // Expire offer
                    $offer->update(['status' => 0]);
                }
            }

        } catch (\Exception $e) {
            Log::error("Error updating offer statuses: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("UpdateOfferStatusJob failed: " . $exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
