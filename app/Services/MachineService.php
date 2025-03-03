<?php 
namespace App\Services;

use App\Repositories\MachineRepository;

class MachineService
{
    public function __construct(protected MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    public function getMachines()
    {
        return $this->machineRepository->getAllMachines();
    }

    public function createMachine(array $machineData, array $branchIds)
    {
        return $this->machineRepository->createMachine($machineData, $branchIds);
    }

    public function showMachine($machineId)
    {
        return $this->machineRepository->findById($machineId);
    }

    public function updateMachine($machine, array $data)
    {
        return $this->machineRepository->updateMachine($machine, $data);
    }

    public function deleteMachine($machine)
    {
        return $this->machineRepository->deleteMachine($machine);
    }
}