<?php

namespace App\Http\Controllers\Web\User;

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

    public function index(SiteSetting $siteSetting)
    {
        $classes = $this->classService->getClasses(siteSettingId: $siteSetting->id);
        return view('user.class-timetable', compact('classes'));
    }

    public function show(SiteSetting $siteSetting , ClassModel $class)
    {
        return view('user.class-details', compact('class'));
    }
}
