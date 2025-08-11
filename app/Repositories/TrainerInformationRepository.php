<?php

namespace App\Repositories;

use App\Models\TrainerInformation;

class TrainerInformationRepository
{
    public function __construct(protected TrainerInformation $model)
    {
        $this->model = $model;
    }

    /**
     * Create trainer information
     */
    public function create(array $data): TrainerInformation
    {
        return $this->model->create($data);
    }

    /**
     * Update trainer information
     */
    public function update(TrainerInformation $trainerInformation, array $data): TrainerInformation
    {
        $trainerInformation->update($data);
        return $trainerInformation;
    }

    /**
     * Find trainer information by user ID
     */
    public function findByUserId(int $userId): ?TrainerInformation
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Find or create trainer information by user ID
     */
    public function findOrCreateByUserId(int $userId, array $data = []): TrainerInformation
    {
        return $this->model->firstOrCreate(['user_id' => $userId], $data);
    }

    /**
     * Delete trainer information
     */
    public function delete(TrainerInformation $trainerInformation): bool
    {
        return $trainerInformation->delete();
    }
}
