<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Features\{AddFeatureRequest, UpdateFeatureRequest};
use App\Models\Feature;
use App\Services\FeatureService;
use Exception;

class FeatureController extends Controller
{
    public function __construct(protected FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    public function index()
    {
        $features = $this->featureService->getFeatures();
        return view('admin.features.index', get_defined_vars());
    }

    public function create()
    {
        return view('admin.features.create');
    }

    public function store(AddFeatureRequest $request)
    {
        try {
            $data = $request->validated();
            $this->featureService->createFeature($data);
            return redirect()->route('features.index')->with('success', 'Feature created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while adding a new feature, please try again in a few minutes.');
        }
    }

    public function show(Feature $feature)
    {
        $feature = $this->featureService->showFeature($feature);
        return view('admin.features.show', get_defined_vars());
    }

    public function edit(Feature $feature)
    {
        $feature = $this->featureService->showFeature($feature);
        return view('admin.features.edit', get_defined_vars());
    }

    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        try {
            $data = $request->validated();
            $this->featureService->updateFeature($feature, $data);
            return redirect()->route('features.index')->with('success', 'Feature updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating the feature, please try again in a few minutes.');
        }
    }

    public function destroy(Feature $feature)
    {
        try {
            $this->featureService->deleteFeature($feature);
            return redirect()->route('features.index')->with('success', 'Feature deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting the feature, please try again in a few minutes.');
        }
    }
}
