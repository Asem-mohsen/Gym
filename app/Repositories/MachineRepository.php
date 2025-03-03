<?php 
namespace App\Repositories;

use App\Models\Machine;

class MachineRepository
{
    public function getAllMachines()
    {
        return Machine::with('branches')->get();
    }

    public function createMachine(array $machineData, array $branchIds)
    {
        $machine = Machine::create($machineData);

        $machine->branches()->sync($branchIds);

        return $machine;
    }

    public function updateMachine(Machine $machine, array $data)
    {
        $machine->update($data);

        return $machine;
    }

    public function deleteMachine(Machine $machine)
    {
        return $machine->delete();
    }

    public function findById(int $id): ?Machine
    {
        return Machine::with('branches')->find($id);
    }
}
