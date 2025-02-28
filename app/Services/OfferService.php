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

    public function getOffers()
    {
        return $this->offerRepository->getAllOffers();
    }

    public function fetchMemberships()
    {
        return $this->membershipRepository->selectMemberships();
    }

    public function fetchServices()
    {
        return $this->serviceRepository->selectServices();
    }
    
    public function fetchOffers()
    {
        return $this->offerRepository->selectOffers();
    }

    public function createOffer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $offer = $this->offerRepository->createOffer($data);
           
            $this->offerRepository->assignOfferables($offer, $data);

            return $offer;
        });
    }

    public function updateOffer($offer, array $data)
    {
        return $this->offerRepository->updateOffer($offer, $data);
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