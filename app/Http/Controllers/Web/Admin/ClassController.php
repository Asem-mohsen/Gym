<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classes\{ StoreClassRequest, UpdateClassRequest };
use App\Models\ClassModel;
use App\Repositories\{ ClassRepository, UserRepository };
use App\Services\{ ClassService, SiteSettingService };
use Illuminate\Http\Request;

class ClassController extends Controller
{
    protected $classService;
    protected $classRepository;
    protected $userRepository;
    protected int $siteSettingId;
    public function __construct(ClassService $classService, ClassRepository $classRepository, UserRepository $userRepository, protected SiteSettingService $siteSettingService)
    {
        $this->classService = $classService;
        $this->classRepository = $classRepository;
        $this->userRepository = $userRepository;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $type = $request->get('type');
        
        $classes = $this->classService->getClassesWithPagination($this->siteSettingId, $perPage, $search, $type);
        
        // Get class types for filter dropdown
        $classTypes = $this->classRepository->getClassTypes($this->siteSettingId);
        
        return view('admin.classes.index', compact('classes', 'classTypes'));
    }

    public function create()
    {
        $trainers = $this->userRepository->getAllTrainers($this->siteSettingId);
        return view('admin.classes.create', compact('trainers'));
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
        return view('admin.classes.edit', compact('class', 'trainers'));
    }

    public function update(UpdateClassRequest $request, ClassModel $class)
    {
        $data = $request->validated();
        $data['image'] = $request->file('image');
        $this->classService->updateClass($class, $data);
        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy($id)
    {
        $class = $this->classRepository->findById($id);
        $this->classService->deleteClass($class);
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
} 