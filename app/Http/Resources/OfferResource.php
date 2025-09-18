<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'title' => $this->getTranslation('title', $locale),
            'description' => $this->getTranslation('description', $locale),
            'discount_percentage' => $this->discount_percentage,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'remaining_days' => $this->remaining_days,
            'is_active' => $this->isActive(),
        ];
    }
    
}
