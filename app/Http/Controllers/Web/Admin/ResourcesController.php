<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\ResourcesService;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    protected $resourcesService;
    protected $siteSettingService;
    protected $siteSettingId;

    public function __construct(ResourcesService $resourcesService, SiteSettingService $siteSettingService)
    {
        $this->resourcesService = $resourcesService;
        $this->siteSettingId = $siteSettingService->getCurrentSiteSettingId();
    }

    public function index(Request $request)
    {
        $data = $this->resourcesService->getDocumentsForGym($request, $this->siteSettingId);
        
        return view('admin.resources', $data);
    }

    public function download(Document $document)
    {
        return $this->resourcesService->downloadDocument($document, $this->siteSettingId);
    }
}
