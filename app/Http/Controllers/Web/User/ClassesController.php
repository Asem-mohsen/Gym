<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\{ClassModel, SiteSetting};
use App\Services\{BlogService, ClassService};

class ClassesController extends Controller
{
    public function __construct(protected ClassService $classService, protected BlogService $blogService)
    {
    }

    public function index(SiteSetting $siteSetting)
    {
        $classes = $this->classService->getClasses(siteSettingId: $siteSetting->id);
        $classesWithSchedules = $this->classService->getClassesWithSchedules($siteSetting->id);
        $timetableData = $this->classService->getTimetableData($siteSetting->id);
        $classTypes = $this->classService->getClassTypes($siteSetting->id);

        return view('user.classes.index', compact('classes', 'timetableData', 'classTypes', 'siteSetting'));
    }

    public function show(SiteSetting $siteSetting , ClassModel $class)
    {
        if ($class->site_setting_id !== $siteSetting->id) {
            abort(404, 'Class not found in this gym.');
        }

        $class = $this->classService->showClass($class);

        $blogPosts = $this->blogService->getBlogPosts(siteSettingId: $siteSetting->id);
        $categories = $this->blogService->getCategories(withCount: ['blogPosts']);
        $tags = $this->blogService->getTags(withCount: ['blogPosts']);
        
        $timetableData = $this->classService->getTimetableData($siteSetting->id);
        $classTypes = $this->classService->getClassTypes($siteSetting->id);
        
        return view('user.classes.class-details', compact('class', 'blogPosts', 'categories', 'tags', 'timetableData', 'classTypes', 'siteSetting'));
    }
}
