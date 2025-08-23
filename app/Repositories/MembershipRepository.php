<?php 
namespace App\Repositories;

use App\Interfaces\MembershipRepositoryInterface;
use App\Models\Membership;
use App\Models\Offerable;

class MembershipRepository
{
    public function getAllMemberships(int $siteSettingId ,array $select = ['*'], array $with = [], array $where = [], array $orderBy = [] , array $withCount = [])
    {
        return Membership::select($select)
            ->when(! empty($with), fn ($query) => $query->with($with))
            ->when(! empty($where), fn ($query) => $query->where($where))
            ->when(! empty($orderBy), function ($query) use ($orderBy) {
                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }
            })
            ->when(! empty($withCount), fn ($query) => $query->withCount($withCount))
            ->where('site_setting_id', $siteSettingId)
            ->get()
            ->map(function ($membership) {
                $membership->price = $this->calculateDiscountedPrice($membership);
                return $membership;
            });
    }

    public function createMembership(array $data)
    {
        return Membership::create($data);
    }

    public function updateMembership(Membership $membership , array $data)
    {
        $membership->update($data);
        return $membership;
    }

    public function deleteMembership(Membership $membership)
    {
        $membership->delete();
    }

    public function findById(int $id, array $with = []): ?Membership
    {
        $membership = Membership::with($with)->find($id);
    
        if ($membership) {
            $membership->price = $this->calculateDiscountedPrice($membership);
        }
    
        return $membership;
    }

    public function selectMemberships(int $siteSettingId)
    {
        return Membership::where('site_setting_id', $siteSettingId)->select('id', 'name')->get()->map(function ($membership) {
            return [
                'id' => $membership->id,
                'name' => $membership->getTranslation('name', app()->getLocale()),
            ];
        });
    }

    private function calculateDiscountedPrice(Membership $membership)
    {
        $offerable = Offerable::where('offerable_type', Membership::class)
            ->where('offerable_id', $membership->id)
            ->with('offer')
            ->first();

        if (!$offerable || !$offerable->offer) {
            return $membership->price;
        }

        $discountType = $offerable->offer->discount_type;
        $discountValue = $offerable->offer->discount_value;
        $originalPrice = $membership->price;

        return match ($discountType) {
            'percentage' => $originalPrice - ($originalPrice * ($discountValue / 100)),
            'fixed' => max(0, $originalPrice - $discountValue),
            default => $originalPrice,
        };
    }
}