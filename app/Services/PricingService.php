<?php

namespace App\Services;

use App\Models\{Service, ClassModel, ClassPricing, Membership, Offer};
use App\Repositories\{ServiceRepository, MembershipRepository};
use InvalidArgumentException;

class PricingService
{
    private const BOOKABLE_TYPES = [
        'service' => Service::class,
        'class' => ClassModel::class,
        'membership' => Membership::class,
    ];

    public function __construct(
        private ServiceRepository $serviceRepository,
        private MembershipRepository $membershipRepository
    ) {}

    /**
     * Calculate the final amount for a bookable item with automatic offer detection
     */
    public function calculateAmount(string $type, int $bookableId, ?int $pricingId = null): float
    {
        $basePrice = $this->getBasePrice($type, $bookableId, $pricingId);
        
        $bestOffer = $this->getBestAvailableOffer($type, $bookableId, $pricingId);
        
        if (!$bestOffer) {
            return $basePrice;
        }

        return $this->applyOffer($basePrice, $bestOffer);
    }

    /**
     * Get the base price for a bookable item
     */
    private function getBasePrice(string $type, int $bookableId, ?int $pricingId = null): float
    {
        return match ($type) {
            'service' => $this->getServicePrice($bookableId),
            'class' => $this->getClassPrice($bookableId, $pricingId),
            'membership' => $this->getMembershipPrice($bookableId),
            default => throw new InvalidArgumentException("Unsupported bookable type: {$type}")
        };
    }

    /**
     * Get service price
     */
    private function getServicePrice(int $serviceId): float
    {
        $service = Service::select('price')->find($serviceId);
        
        if (!$service) {
            throw new InvalidArgumentException("Service not found with ID: {$serviceId}");
        }

        return (float) $service->price;
    }

    /**
     * Get class price from pricing table
     */
    private function getClassPrice(int $classId, ?int $pricingId = null): float
    {
        if (!$pricingId) {
            throw new InvalidArgumentException("Pricing ID is required for class bookings");
        }

        $pricing = ClassPricing::where('class_id', $classId)
            ->where('id', $pricingId)
            ->first();

        if (!$pricing) {
            throw new InvalidArgumentException("Class pricing not found for class ID: {$classId}, pricing ID: {$pricingId}");
        }

        return (float) $pricing->price;
    }

    /**
     * Get membership price
     */
    private function getMembershipPrice(int $membershipId): float
    {
        $membership = Membership::select('price')->find($membershipId);
        
        if (!$membership) {
            throw new InvalidArgumentException("Membership not found with ID: {$membershipId}");
        }

        return (float) $membership->price;
    }

    /**
     * Get the best available offer for the bookable item
     */
    private function getBestAvailableOffer(string $type, int $bookableId, ?int $pricingId = null): ?Offer
    {
        $modelClass = self::BOOKABLE_TYPES[$type];
        
        // Get base price without offer to avoid circular dependency
        $basePrice = match ($type) {
            'service' => $this->getServicePrice($bookableId),
            'class' => $this->getClassPrice($bookableId, $pricingId),
            'membership' => $this->getMembershipPrice($bookableId),
            default => throw new InvalidArgumentException("Unsupported bookable type: {$type}")
        };

        $availableOffers = Offer::where('status', 1)
            ->whereHas('offerables', function ($query) use ($modelClass, $bookableId) {
                $query->where('offerable_type', $modelClass)
                      ->where('offerable_id', $bookableId);
            })
            ->whereDate('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhereDate('end_date', '>=', now());
            })
            ->get();

        if ($availableOffers->isEmpty()) {
            return null;
        }

        // Find the offer that gives the maximum discount
        $bestOffer = null;
        $maxDiscount = 0;

        foreach ($availableOffers as $offer) {
            $discountAmount = $this->calculateDiscountAmount($basePrice, $offer);
            
            if ($discountAmount > $maxDiscount) {
                $maxDiscount = $discountAmount;
                $bestOffer = $offer;
            }
        }

        return $bestOffer;
    }

    /**
     * Calculate the discount amount for a given offer
     */
    private function calculateDiscountAmount(float $basePrice, Offer $offer): float
    {
        return match ($offer->discount_type) {
            'percentage' => $basePrice * ($offer->discount_value / 100),
            'fixed' => min($basePrice, $offer->discount_value),
            default => 0
        };
    }

    /**
     * Apply offer discount to the base price
     */
    private function applyOffer(float $basePrice, Offer $offer): float
    {
        return match ($offer->discount_type) {
            'percentage' => $basePrice * (1 - ($offer->discount_value / 100)),
            'fixed' => max(0, $basePrice - $offer->discount_value),
            default => $basePrice
        };
    }
}
