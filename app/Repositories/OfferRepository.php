<?php 
namespace App\Repositories;

use App\Models\Membership;
use App\Models\Offer;
use App\Models\Offerable;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class OfferRepository
{
    public function getAllOffers()
    {
        return Offer::all();
    }

    public function createOffer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $offer = Offer::create([
                'title'       => $data['title'],
                'description' => $data['description'],
                'discount_type'  => $data['discount_type'],
                'discount_value'=> $data['discount_value'],
                'start_date'  => $data['start_date'],
                'end_date'    => $data['end_date'],
                'status'      => now()->toDateString() === $data['start_date'] ? 1 : 0,
            ]);

            return $offer;
        });
    }

    public function assignOfferables(Offer $offer, array $data)
    {
        $offerables = [];

        // Process Memberships
        if (in_array("App\Models\Membership", $data['assign_to'])) {
            $membershipIds = $data['memberships'] ?? [];
            if (in_array('all', $membershipIds)) {
                $membershipIds = Membership::pluck('id')->toArray();
            }

            foreach ($membershipIds as $id) {
                $offerables[] = [
                    'offer_id'       => $offer->id,
                    'offerable_type' => Membership::class,
                    'offerable_id'   => $id,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
        }

        // Process Services
        if (in_array("App\Models\Service", $data['assign_to'])) {
            $serviceIds = $data['services'] ?? [];
            if (in_array('all', $serviceIds)) {
                $serviceIds = Service::pluck('id')->toArray();
            }

            foreach ($serviceIds as $id) {
                $offerables[] = [
                    'offer_id'       => $offer->id,
                    'offerable_type' => Service::class,
                    'offerable_id'   => $id,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
        }

        Offerable::insert($offerables);
    }

    public function updateOffer(Offer $offer, array $data)
    {
        return DB::transaction(function () use ($offer, $data) {
            $offer->update([
                'title'          => $data['title'],
                'description'    => $data['description'],
                'start_date'     => $data['start_date'],
                'end_date'       => $data['end_date'],
                'discount_type'  => $data['discount_type'],
                'discount_value' => $data['discount_value'],
                'status'         => now()->toDateString() === $data['start_date'] ? 1 : 0,
                'update_at'      => now(),
            ]);
    
            $offer->offerables()->delete();
    
            $this->assignOfferables($offer, $data);
    
            return $offer;
        });
    }
    
    public function deleteOffer(Offer $offer)
    {
        return $offer->delete();
    }

    public function findById(int $id)
    {
        $offer = Offer::with('offerables')->findOrFail($id);
    
        $assignedModels = $offer->offerables->pluck('offerable_type')->unique()->toArray();
    
        $selectedMemberships = $offer->offerables->where('offerable_type', 'App\Models\Membership')->pluck('offerable_id')->toArray();
        $selectedServices = $offer->offerables->where('offerable_type', 'App\Models\Service')->pluck('offerable_id')->toArray();
    
        $memberships = Membership::all();
        $services = Service::all();
    
        // Check if "All" was selected
        $allMembershipsSelected = count($selectedMemberships) === Membership::count();
        $allServicesSelected = count($selectedServices) === Service::count();
    
        return [
            'offer'                 => $offer,
            'assignedModels'        => $assignedModels,
            'memberships'           => $memberships,
            'services'              => $services,
            'selectedMemberships'   => $selectedMemberships,
            'selectedServices'      => $selectedServices,
            'allMembershipsSelected' => $allMembershipsSelected,
            'allServicesSelected'   => $allServicesSelected
        ];
    }
    
    public function selectOffers()
    {
        return Offer::select('id', 'title')->get()->map(function ($offer) {
            return [
                'id' => $offer->id,
                'name' => $offer->getTranslation('title', app()->getLocale()),
            ];
        });
    }
}
