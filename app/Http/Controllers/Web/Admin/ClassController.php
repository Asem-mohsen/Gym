<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classes\{ StoreClassRequest, UpdateClassRequest };
use App\Models\ClassModel;
use App\Repositories\{ ClassRepository, UserRepository };
use App\Services\{ ClassService, SiteSettingService, BranchService };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClassController extends Controller
{
    protected $classService;
    protected $classRepository;
    protected $userRepository;
    protected int $siteSettingId;
    public function __construct(ClassService $classService, ClassRepository $classRepository, UserRepository $userRepository, protected SiteSettingService $siteSettingService, protected BranchService $branchService)
    {
        $this->classService = $classService;
        $this->classRepository = $classRepository;
        $this->userRepository = $userRepository;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index(Request $request)
    {
        $type = $request->get('type');
        $branchId = $request->get('branch_id');

        $branches = $this->branchService->getBranches($this->siteSettingId);

        $classes = $this->classService->getClassesWithPagination($this->siteSettingId,$type,$branchId);
        
        $classTypes = $this->classRepository->getClassTypes($this->siteSettingId);
        
        return view('admin.classes.index', compact('classes', 'classTypes'));
    }

    public function create()
    {
        $trainers = $this->userRepository->getAllTrainers($this->siteSettingId);
        $branches = $this->branchService->getBranches($this->siteSettingId);
        return view('admin.classes.create', compact('trainers', 'branches'));
    }

    public function store(StoreClassRequest $request)
    {
        $data = $request->validated();
        $data['image'] = $request->file('image');

        $this->classService->createClass($data, $this->siteSettingId);
        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(ClassModel $class)
    {
        $trainers = $this->userRepository->getAllTrainers($this->siteSettingId);
        $branches = $this->branchService->getBranches($this->siteSettingId);
        return view('admin.classes.edit', compact('class', 'trainers', 'branches'));
    }

    public function update(UpdateClassRequest $request, ClassModel $class)
    {
        try {
            $data = $request->validated();
            $data['image'] = $request->file('image');
            $this->classService->updateClass($class, $data);
            return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating class: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating class. Please try again.');
        }
    }

    public function destroy($id)
    {
        $class = $this->classRepository->findById($id);
        $this->classService->deleteClass($class);
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
} 