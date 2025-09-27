<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Services\GymFeatureService;
use Illuminate\Support\Facades\Session;

class GymFeatureComposer
{
    protected $gymFeatureService;

    public function __construct(GymFeatureService $gymFeatureService)
    {
        $this->gymFeatureService = $gymFeatureService;
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $gymId = Session::get('current_gym_id');
        
        if ($gymId) {
            $features = $this->gymFeatureService->getFeatureAvailability($gymId);
            $view->with('gymFeatures', $features);
        } else {
            // Default to all features hidden if no gym context
            $view->with('gymFeatures', [
                'checkin' => false,
                'classes' => false,
                'services' => false,
                'gallery' => false,
                'blog' => false,
                'team' => false,
            ]);
        }
    }
}
