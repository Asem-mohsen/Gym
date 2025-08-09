<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Offeres\{ AddOfferRequest , UpdateOfferRequest};
use App\Models\Offer;
use App\Services\{ OfferService , SiteSettingService};
use Exception;

class OfferController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected OfferService $offerService,  protected SiteSettingService $siteSettingService)
    {
        $this->offerService = $offerService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index()
    {
        $offers = $this->offerService->getOffers($this->siteSettingId);
        return view('admin.offers.index',compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(AddOfferRequest $request)
    {
        try {
            $this->offerService->createOffer($request->validated(), $this->siteSettingId);
            return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating offer, please try again in a few minutes.');
        }
    }

    public function edit(Offer $offer)
    {
        $data = $this->offerService->showOffer($offer->id);
    
        return view('admin.offers.edit', [
            'offer'                 => $data['offer'],
            'assignedModels'        => $data['assignedModels'],
            'memberships'           => $data['memberships'],
            'services'              => $data['services'],
            'selectedMemberships'   => $data['selectedMemberships'],
            'selectedServices'      => $data['selectedServices'],
            'allMembershipsSelected'=> $data['allMembershipsSelected'],
            'allServicesSelected'   => $data['allServicesSelected'],
        ]);
    }
    

    public function update(UpdateOfferRequest $request , Offer $offer)
    {
        try {
            $this->offerService->updateOffer($offer , $request->validated() , $this->siteSettingId);
            return redirect()->route('offers.index')->with('success', 'Offer updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating offer, please try again in a few minutes.');
        }
    }

    public function destroy(Offer $offer)
    {
        try {
            $this->offerService->deleteOffer($offer);
            return redirect()->route('offers.index')->with('success', 'Offer deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('offers.index')->with('success', 'Error deleting offer, please try again..');
        }
    }

    public function getMemberships()
    {
        $memberships = $this->offerService->fetchMemberships($this->siteSettingId);
        return response()->json($memberships);
    }

    public function getServices()
    {
        $services = $this->offerService->fetchServices($this->siteSettingId);
        return response()->json($services);
    }
}
