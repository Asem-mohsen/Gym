<?php

namespace App\Jobs;

use Exception;
use Throwable;
use App\Mail\GymDeactivationNotificationEmail;
use App\Models\{SiteSetting, User};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\{Mail, Log};

class SendGymDeactivationEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300; // 5 minutes timeout

    public function __construct(
        protected SiteSetting $gym
    ) {}

    public function handle(): void
    {
        try {
            $regularUsers = $this->getRegularUsersForGym();

            if ($regularUsers->isEmpty()) {
                Log::info('No regular users found for gym deactivation emails', [
                    'gym_id' => $this->gym->id
                ]);
                return;
            }

            $successCount = 0;
            $failureCount = 0;

            foreach ($regularUsers as $user) {
                try {
                    Mail::to($user->email)->send(new GymDeactivationNotificationEmail($user, $this->gym));

                    $successCount++;
                    
                    Log::info('Gym deactivation email sent successfully', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'gym_id' => $this->gym->id
                    ]);

                } catch (Exception $e) {
                    $failureCount++;
                    
                    Log::error('Failed to send gym deactivation email', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'gym_id' => $this->gym->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Gym deactivation email job completed', [
                'gym_id' => $this->gym->id,
                'total_users' => $regularUsers->count(),
                'success_count' => $successCount,
                'failure_count' => $failureCount
            ]);

        } catch (Exception $e) {
            Log::error('Gym deactivation email job failed', [
                'gym_id' => $this->gym->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Get all regular users associated with this gym
     */
    private function getRegularUsersForGym()
    {
        return User::where('is_admin', 0)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'regular_user');
            })
            ->whereHas('gyms', function ($query) {
                $query->where('site_setting_id', $this->gym->id);
            })
            ->where('status', 1)
            ->whereNotNull('email')
            ->get();
    }

    /**
     * Handle job failure
     */
    public function failed(Throwable $exception): void
    {
        Log::error('Gym deactivation email job failed permanently', [
            'gym_id' => $this->gym->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
