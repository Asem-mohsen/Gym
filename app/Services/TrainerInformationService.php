<?php

namespace App\Services;

use App\Repositories\TrainerInformationRepository;
use App\Models\TrainerInformation;

class TrainerInformationService
{
    public function __construct(protected TrainerInformationRepository $trainerInformationRepository)
    {
        $this->trainerInformationRepository = $trainerInformationRepository;
    }

    /**
     * Create or update trainer information
     */
    public function createOrUpdateTrainerInformation(int $userId, array $data): TrainerInformation
    {
        // Remove user_id from data if present to avoid conflicts
        unset($data['user_id']);
        
        return $this->trainerInformationRepository->findOrCreateByUserId($userId, $data);
    }

    /**
     * Get trainer information by user ID
     */
    public function getTrainerInformationByUserId(int $userId): ?TrainerInformation
    {
        return $this->trainerInformationRepository->findByUserId($userId);
    }

    /**
     * Update trainer information
     */
    public function updateTrainerInformation(int $userId, array $data): TrainerInformation
    {
        $trainerInformation = $this->trainerInformationRepository->findByUserId($userId);
        
        if (!$trainerInformation) {
            throw new \Exception('Trainer information not found');
        }

        return $this->trainerInformationRepository->update($trainerInformation, $data);
    }

    /**
     * Delete trainer information
     */
    public function deleteTrainerInformation(int $userId): bool
    {
        $trainerInformation = $this->trainerInformationRepository->findByUserId($userId);
        
        if (!$trainerInformation) {
            return false;
        }

        return $this->trainerInformationRepository->delete($trainerInformation);
    }
}
