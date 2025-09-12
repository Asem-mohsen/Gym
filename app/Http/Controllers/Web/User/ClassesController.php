<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Models\{ClassModel, SiteSetting};
use App\Services\{BlogService, BookingService, ClassService, BranchService};
use Illuminate\Support\Facades\Auth;

class ClassesController extends Controller
{
    public function __construct(protected ClassService $classService, protected BlogService $blogService, protected BranchService $branchService, protected BookingService $bookingService)
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

    public function book(StoreBookingRequest $request, SiteSetting $siteSetting, ClassModel $class)
    {
        $validated = $request->validated();

        $pricing = $class->pricings()->first();

        if (!$pricing) {
            return back()->with('error', 'No pricing available for this class.');
        }

        $booking = $this->bookingService->createBooking([
            'user_id' => Auth::id(),
            'bookable_type' => ClassModel::class,
            'bookable_id' => $class->id,
            'branch_id' => $validated['branch_id'],
            'schedule_id' => $validated['schedule_id'],
            'pricing_id' => $pricing->id,
            'booking_date' => $validated['booking_date'] ?? now(),
            'amount' => $pricing->price,
            'status' => 'pending',
        ]);

        return redirect()->route('user.payment.create', ['siteSetting' => $siteSetting->slug, 'bookingId' => $booking->id])->with('success', 'Booking created successfully');
    }

}
