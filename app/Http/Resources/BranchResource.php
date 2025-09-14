<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'location' => $this->getTranslation('location', $locale),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'city' => $this->city,
            'region' => $this->region,
            'country' => $this->country,
            'type' => $this->type,
            'map_url' => $this->map_url,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'x_url' => $this->x_url,
        ];
    }
}
