<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class CleanupExpiredNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired notifications from the database';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Starting expired notification cleanup process...');

        try {
            $isDryRun = $this->option('dry-run');
            
            if ($isDryRun) {
                $this->warn('DRY RUN MODE - No notifications will be deleted');
            }

            // Get count of expired notifications
            $expiredCount = $notificationService->getExpiredNotificationsCount();
            
            if ($expiredCount === 0) {
                $this->info('No expired notifications found.');
                return 0;
            }

            $this->info("Found {$expiredCount} expired notification(s)");

            if ($isDryRun) {
                $this->info('These notifications would be deleted in production mode.');
                return 0;
            }

            // Confirm deletion
            if (!$this->confirm("Are you sure you want to delete {$expiredCount} expired notification(s)?")) {
                $this->info('Cleanup cancelled.');
                return 0;
            }

            // Perform cleanup
            $deletedCount = $notificationService->cleanupExpiredNotifications();
            
            $this->info("✓ Successfully deleted {$deletedCount} expired notification(s)");
            
            Log::info('Expired notifications cleanup completed', [
                'deleted_count' => $deletedCount
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error('Error during notification cleanup: ' . $e->getMessage());
            Log::error('Notification cleanup command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
