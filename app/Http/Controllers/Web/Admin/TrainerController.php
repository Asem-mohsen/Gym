<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\{ AddUserRequest , UpdateUserRequest};
use App\Models\User;
use App\Services\{UserService , RoleService, SiteSettingService, BranchService, RoleAssignmentService};
use Exception;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    protected int $siteSettingId;

    public function __construct(
        protected UserService $userService, 
        protected RoleService $roleService, 
        protected SiteSettingService $siteSettingService, 
        protected BranchService $branchService, 
        protected RoleAssignmentService $roleAssignmentService
    ) {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->branchService = $branchService;
        $this->siteSettingService = $siteSettingService;
        $this->roleAssignmentService = $roleAssignmentService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index(Request $request)
    {
        try {
            $trainers = $this->userService->getTrainers(
                $this->siteSettingId,
                $request->get('per_page', 15),
                $request->get('branch_id'),
                $request->get('search')
            );
            
            $branches = $this->branchService->getBranches($this->siteSettingId);
            
            foreach ($trainers as $trainer) {
                $trainer->has_set_password = $trainer->hasSetPassword();
            }
            
            return view('admin.trainers.index', compact('trainers', 'branches'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while fetching trainers, please try again in a few minutes.');
        }
    }

    public function create()
    {
        $roles = $this->roleAssignmentService->getRoles(['trainer']);

        $trainerRoles = collect($roles)->filter(function($role) {
            return in_array($role['name'], ['trainer']);
        })->toArray();
        
        return view('admin.trainers.create', compact('trainerRoles'));
    }

    public function edit(Request $request, User $trainer)
    {
        if (!$trainer->hasRole('trainer')) {
            return redirect()->back()->with('error', 'Selected user is not a trainer.');
        }

        $roles = $this->roleAssignmentService->getRoles(['trainer']);

        $trainerRoles = collect($roles)->filter(function($role) {
            return in_array($role['name'], ['trainer']);
        })->toArray();
        
        return view('admin.trainers.edit', compact('trainer', 'trainerRoles'));
    }

    public function show(Request $request, User $trainer)
    {
        if (!$trainer->hasRole('trainer')) {
            return redirect()->back()->with('error', 'Selected user is not a trainer.');
        }

        $trainer->load(['photos' => function($query) {
            $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }]);

        return view('admin.trainers.show', compact('trainer'));
    }

    public function store(AddUserRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            $this->userService->createAdminUser($data, $this->siteSettingId);
            return redirect()->route('trainers.index')->with('success', 'Trainer created successfully. An onboarding email has been sent to set their password.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating new trainer, please try again in a few minutes.');
        }
    }

    public function update(UpdateUserRequest $request, User $trainer)
    {
        try {
            if (!$trainer->hasRole('trainer')) {
                return redirect()->back()->with('error', 'Selected user is not a trainer.');
            }

            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            $this->userService->updateUser($trainer, $data, $this->siteSettingId);
            return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating trainer, please try again in a few minutes.');
        }
    }

    public function destroy(User $trainer)
    {
        try {
            // Ensure the user is actually a trainer
            if (!$trainer->hasRole('trainer')) {
                return redirect()->back()->with('error', 'Selected user is not a trainer.');
            }

            // Get the site setting for the trainer
            $site = $trainer->getCurrentSite();
            
            $this->userService->deleteUser($trainer, $site);
            return redirect()->route('trainers.index')->with('success', 'Trainer deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting trainer, please try again in a few minutes.');
        }
    }

    public function resendOnboardingEmail(User $trainer)
    {
        try {
            // Ensure the user is actually a trainer
            if (!$trainer->hasRole('trainer')) {
                return redirect()->back()->with('error', 'Selected user is not a trainer.');
            }

            if ($trainer->hasSetPassword()) {
                return redirect()->back()->with('error', 'This user has already set their password. Onboarding email is not needed.');
            }

            $this->userService->sendOnboardingEmail($trainer, $this->siteSettingId);
            return redirect()->back()->with('success', 'Onboarding email has been resent successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while resending onboarding email, please try again in a few minutes.');
        }
    }
}
