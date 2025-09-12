<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\SiteSetting;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendMembershipExpirationNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:membership-expiration {--gym-id= : Specific gym ID to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send membership expiration notifications to admins';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Starting membership expiration notification process...');

        try {
            $gymId = $this->option('gym-id');
            
            if ($gymId) {
                // Process specific gym
                $siteSetting = SiteSetting::find($gymId);
                if (!$siteSetting) {
                    $this->error("Gym with ID {$gymId} not found.");
                    return 1;
                }
                
                $this->processGym($notificationService, $siteSetting);
            } else {
                // Process all gyms
                $siteSettings = SiteSetting::all();
                $this->info("Processing {$siteSettings->count()} gym(s)...");
                
                foreach ($siteSettings as $siteSetting) {
                    $this->processGym($notificationService, $siteSetting);
                }
            }

            $this->info('Membership expiration notification process completed successfully.');
            return 0;

        } catch (Exception $e) {
            $this->error('Error processing membership expiration notifications: ' . $e->getMessage());
            Log::error('Membership expiration notification command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Process a single gym for membership expiration notifications
     */
    private function processGym(NotificationService $notificationService, SiteSetting $siteSetting): void
    {
        $this->info("Processing gym: {$siteSetting->getTranslation('gym_name', app()->getLocale())} (ID: {$siteSetting->id})");
        
        try {
            $success = $notificationService->sendMembershipExpirationNotifications($siteSetting);
            
            if ($success) {
                $this->info("✓ Successfully processed gym: {$siteSetting->getTranslation('gym_name', app()->getLocale())}");
            } else {
                $this->warn("⚠ No notifications sent for gym: {$siteSetting->getTranslation('gym_name', app()->getLocale())}");
            }
            
        } catch (Exception $e) {
            $this->error("✗ Error processing gym {$siteSetting->getTranslation('gym_name', app()->getLocale())}: " . $e->getMessage());
            Log::error('Error processing gym for membership expiration notifications', [
                'gym_id' => $siteSetting->id,
                'gym_name' => $siteSetting->getTranslation('gym_name', app()->getLocale()),
                'error' => $e->getMessage()
            ]);
        }
    }
}
