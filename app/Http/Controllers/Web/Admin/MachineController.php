<?php

namespace App\Http\Controllers\Web\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Machines\AddMachineRequest;
use App\Models\Machine;
use App\Services\{ BranchService , MachineService, SiteSettingService};
use Exception;

class MachineController extends Controller
{
    public function __construct(protected MachineService $machineService , protected BranchService $branchService, protected SiteSettingService $siteSettingService)
    {
        $this->machineService = $machineService;
        $this->branchService = $branchService;
        $this->siteSettingService = $siteSettingService;
    }

    public function index()
    {
        $machines =  $this->machineService->getMachines();
        return view('admin.machines.index',compact('machines'));
    }


    public function create()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();

        $branches = $this->branchService->getBranches($siteSettingId);
        return view('admin.machines.create',compact('branches'));
    }

    public function store(AddMachineRequest $request)
    {
        $validated = $request->validated();

        try {
            $machineData = collect($validated)->except('branches')->toArray();
            $branchIds = $validated['branches'];
            $this->machineService->createMachine($machineData, $branchIds);
            return redirect()->route('machines.index')->with('success', 'Machine added successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while adding a new machine, please try again in a few minutes.');
        }
    }

    public function show(Machine $machine)
    {
        $machine = $this->machineService->showMachine($machine->id);

        return view('admin.machines.show', get_defined_vars());
    }

    public function edit(Machine $machine)
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();

        $machine  = $this->machineService->showMachine($machine->id);
        $branches = $this->branchService->getBranches($siteSettingId);

        return view('admin.machines.edit', get_defined_vars());
    }

    public function update(AddMachineRequest $request , Machine $machine)
    {
        try {
            $this->machineService->updateMachine($machine, $request->validated());
            return redirect()->route('machines.index')->with('success', 'Machine updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating the machine, please try again in a few minutes.');
        }
    }

    public function destroy(Machine $machine)
    {
        try {
            $this->machineService->deleteMachine($machine);
            return redirect()->route('machines.index')->with('success', 'Machine deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting the machine, please try again in a few minutes.');
        }
    }
}
