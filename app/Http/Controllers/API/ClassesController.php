<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\SiteSetting;
use App\Services\ClassService;

class ClassesController extends Controller
{
    protected $classService;

    public function __construct(ClassService $classService)
    {
        $this->classService = $classService;
    }

    public function index(SiteSetting $gym)
    {
        $classes = $this->classService->getClasses(siteSettingId: $gym->id);
        return successResponse($classes, 'classes data retrieved successfully');
    }

    public function show(SiteSetting $gym , ClassModel $class)
    {
        $class = $this->classService->showClass($class);
        return successResponse($class, 'class data retrieved successfully');
    }
}
