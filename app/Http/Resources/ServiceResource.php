<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'description' => $this->getTranslation('description', $locale),
            'price' => $this->price,
            'duration' => $this->duration,
            'requires_payment' => $this->requires_payment,
            'booking_type' => $this->booking_type,
            'is_available' => $this->is_available,
            'sort_order' => $this->sort_order,
            'image' => $this->getFirstMediaUrl('service_image'),
            'branches' => BranchResource::collection($this->whenLoaded('branches')),
            'offers' => OfferResource::collection($this->whenLoaded('offers')),
        ];
    }
}