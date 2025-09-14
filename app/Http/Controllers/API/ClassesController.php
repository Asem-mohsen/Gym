<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ClassResource;
use App\Models\ClassModel;
use App\Models\SiteSetting;
use App\Services\ClassService;
use App\Http\Controllers\Controller;

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

        $data = [
            'classes' => ClassResource::collection($classes),
            'class_types' => $this->classService->getClassTypes(siteSettingId: $gym->id),
        ];

        return successResponse($data, 'classes data retrieved successfully');
    }

    public function show(SiteSetting $gym , ClassModel $class)
    {
        $class = $this->classService->showClass($class);
        return successResponse(new ClassResource($class), 'class data retrieved successfully');
    }
}
