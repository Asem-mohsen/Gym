<?php 
namespace App\Services;

use App\Repositories\{MembershipRepository, ServiceRepository, OfferRepository};
use Illuminate\Support\Facades\DB;

class OfferService
{
    public function __construct(
        protected OfferRepository $offerRepository ,
        protected MembershipRepository $membershipRepository ,
        protected ServiceRepository $serviceRepository)
    {
        $this->offerRepository = $offerRepository;
        $this->membershipRepository = $membershipRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function getOffers(int $siteSettingId)
    {
        return $this->offerRepository->getAllOffers($siteSettingId);
    }

    public function fetchMemberships(int $siteSettingId)
    {
        return $this->membershipRepository->selectMemberships($siteSettingId);
    }

    public function fetchServices(int $siteSettingId)
    {
        return $this->serviceRepository->selectServices($siteSettingId);
    }
    
    public function fetchOffers(int $siteSettingId)
    {
        return $this->offerRepository->selectOffers($siteSettingId);
    }

    public function createOffer(array $data, int $siteSettingId)
    {
        return DB::transaction(function () use ($data , $siteSettingId) {
            $offer = $this->offerRepository->createOffer($data , $siteSettingId);
           
            $this->offerRepository->assignOfferables($offer, $data);

            return $offer;
        });
    }

    public function updateOffer($offer, array $data ,int $siteSettingId)
    {
        return $this->offerRepository->updateOffer($offer, $data, $siteSettingId);
    }

    public function showOffer($offerId)
    {
        return $this->offerRepository->findById($offerId);
    }

    public function deleteOffer($offer)
    {
        return $this->offerRepository->deleteOffer($offer);
    }
}