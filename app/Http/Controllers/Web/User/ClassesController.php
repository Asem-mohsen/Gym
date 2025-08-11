<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\SiteSetting;
use App\Services\BlogService;
use App\Services\ClassService;

class ClassesController extends Controller
{
    protected $classService;
    protected $blogService;

    public function __construct(ClassService $classService, BlogService $blogService)
    {
        $this->classService = $classService;
        $this->blogService = $blogService;
    }

    public function index(SiteSetting $siteSetting)
    {
        $classes = $this->classService->getClasses(siteSettingId: $siteSetting->id);
        return view('user.classes.index', compact('classes'));
    }

    public function show(SiteSetting $siteSetting , ClassModel $class)
    {
        $class = $this->classService->showClass($class);
        $blogPosts = $this->blogService->getBlogPosts(siteSettingId: $siteSetting->id);
        $categories = $this->blogService->getCategories(withCount: ['blogPosts']);
        $tags = $this->blogService->getTags(withCount: ['blogPosts']);
        return view('user.classes.class-details', compact('class', 'blogPosts', 'categories', 'tags'));
    }
}
