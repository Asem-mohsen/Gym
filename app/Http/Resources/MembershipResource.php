<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = getLocaleFromRequest($request);
        
        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale),
            'subtitle' => $this->getTranslation('subtitle', $locale),
            'general_description' => $this->getTranslation('general_description', $locale),
            'price' => $this->price,
            'period' => $this->period,
            'billing_interval' => $this->billing_interval,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'offers' => OfferResource::collection($this->whenLoaded('offers')),
        ];
    }
    
}
