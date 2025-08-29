<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\{UserRepository,TrainerInformationRepository};
use Illuminate\Support\Facades\{Hash,DB};

class AccountService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected TrainerInformationRepository $trainerInformationRepository
    ) {}

    /**
     * Update user account details
     */
    public function updateAccount(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            $trainerData = $this->extractTrainerData($data);
            
            $userData = $this->prepareUserData($data);
            $updatedUser = $this->userRepository->updateUser($user, $userData);
            
            if (!empty($trainerData)) {
                $this->updateTrainerInformation($user->id, $trainerData);
            }
            
            return $updatedUser->fresh(['trainerInformation', 'roles']);
        });
    }

    /**
     * Prepare user data for update
     */
    private function prepareUserData(array $data): array
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'gender' => $data['gender'],
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        return $userData;
    }

    /**
     * Extract trainer-specific data from request
     */
    private function extractTrainerData(array $data): array
    {
        $trainerFields = [
            'weight', 'height', 'date_of_birth', 'brief_description',
            'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url'
        ];

        return array_intersect_key($data, array_flip($trainerFields));
    }

    /**
     * Update trainer information
     */
    private function updateTrainerInformation(int $userId, array $data): void
    {
        $trainerInformation = $this->trainerInformationRepository->findByUserId($userId);
        
        if ($trainerInformation) {
            $this->trainerInformationRepository->update($trainerInformation, $data);
        } else {
            $data['user_id'] = $userId;
            $this->trainerInformationRepository->create($data);
        }
    }
}
