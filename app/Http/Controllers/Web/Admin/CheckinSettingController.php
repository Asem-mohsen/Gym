<?php

namespace App\Http\Controllers\Web\Admin;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\CheckinSettingService;
use App\Http\Requests\Admin\CheckinSettingRequest;
use Illuminate\Http\Request;
use App\Services\SiteSettingService;

class CheckinSettingController extends Controller
{
    private $siteSettingId;
    public function __construct(
        private CheckinSettingService $checkinSettingService,
        private SiteSettingService $siteSettingService
    ) {
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    /**
     * Display check-in settings for the gym
     */
    public function index()
    {
        $gym = $this->siteSettingService->getSiteSettingById($this->siteSettingId);
        $checkinSettings = $this->checkinSettingService->getGymCheckinSettings($this->siteSettingId);
        $branches = $gym->branches ?? collect();
        
        $selectedBranches = collect();
        if ($checkinSettings && $checkinSettings->enabled_branches) {
            $selectedBranches = $branches->whereIn('id', $checkinSettings->enabled_branches);
        }

        return view('admin.checkin-settings.index', compact('gym', 'checkinSettings', 'branches', 'selectedBranches'));
    }

    /**
     * Show the form for creating check-in settings
     */
    public function create()
    {
        $gym = $this->siteSettingService->getSiteSettingById($this->siteSettingId);
        $branches = $gym->branches ?? collect();
        $defaultSettings = $this->checkinSettingService->getDefaultSettings();

        return view('admin.checkin-settings.create', compact('branches', 'defaultSettings'));
    }

    /**
     * Store check-in settings
     */
    public function store(CheckinSettingRequest $request)
    {
        $data = $request->validated();
        $data['site_setting_id'] = $this->siteSettingId;

        $this->checkinSettingService->createCheckinSettings($data);

        return redirect()->route('admin.checkin-settings.index')->with('success', 'Check-in settings created successfully.');
    }

    /**
     * Show the form for editing check-in settings
     */
    public function edit()
    {
        $gym = $this->siteSettingService->getSiteSettingById($this->siteSettingId);
        $checkinSetting = $this->checkinSettingService->getGymCheckinSettings($this->siteSettingId);
        
        if (!$checkinSetting) {
            return redirect()->route('admin.checkin-settings.create')->with('error', 'No check-in settings found. Please create them first.');
        }
        
        $branches = $gym->branches ?? collect();
        $defaultSettings = $this->checkinSettingService->getDefaultSettings();

        return view('admin.checkin-settings.edit', compact('checkinSetting', 'branches', 'defaultSettings'));
    }

    /**
     * Update check-in settings
     */
    public function update(CheckinSettingRequest $request)
    {
        $checkinSetting = $this->checkinSettingService->getGymCheckinSettings($this->siteSettingId);
        
        if (!$checkinSetting) {
            return redirect()->route('admin.checkin-settings.create')->with('error', 'No check-in settings found. Please create them first.');
        }
        
        $data = $request->validated();
        $this->checkinSettingService->updateCheckinSettings($checkinSetting, $data);

        return redirect()->route('admin.checkin-settings.index')->with('success', 'Check-in settings updated successfully.');
    }

    /**
     * Toggle check-in method
     */
    public function toggleMethod(Request $request)
    {
        $request->validate([
            'method' => 'required|in:self_scan,gate_scan',
            'enabled' => 'required|boolean'
        ]);

        $this->checkinSettingService->toggleCheckinMethod($this->siteSettingId, $request->method, $request->enabled);

        return response()->json([
            'success' => true,
            'message' => ucfirst($request->method) . ' check-in method ' . ($request->enabled ? 'enabled' : 'disabled') . ' successfully.'
        ]);
    }

    /**
     * Get check-in statistics
     */
    public function stats(Request $request)
    {
        $gym = $this->siteSettingService->getSiteSettingById($this->siteSettingId);
        $period = $request->get('period', 'today');
        $stats = $this->checkinSettingService->getCheckinStats($this->siteSettingId, $period);
        $recentCheckins = $this->checkinSettingService->getRecentCheckins($this->siteSettingId, 6);

        return view('admin.checkin-settings.stats', compact('gym', 'stats', 'recentCheckins', 'period'));
    }

    /**
     * Test QR code generation
     */
    public function testQr()
    {
        try {
            $gym = $this->siteSettingService->getSiteSettingById($this->siteSettingId);
            
            if (!$gym) {
                return response()->json([
                    'error' => 'Gym not found',
                    'site_setting_id' => $this->siteSettingId
                ], 404);
            }
            
            $qrData = $this->checkinSettingService->generateTestQrCode($gym);

            return response()->json($qrData);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to generate QR code',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
