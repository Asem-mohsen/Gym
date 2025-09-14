<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteSettingResource extends JsonResource
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
            'gym_name' => $this->getTranslation('gym_name', $locale),
            'slug' => $this->slug,
            'address' => $this->getTranslation('address', $locale),
            'description' => $this->getTranslation('description', $locale),
            'contact_email' => $this->contact_email,
            'phone' => $this->phone,
            'site_url' => $this->site_url,
            'is_website_visible' => $this->is_website_visible,
            'logo' => $this->getFirstMediaUrl('gym_logo'),
            'site_map' => $this->site_map,
            'facebook_url' => $this->facebook_url,
            'x_url' => $this->x_url,
            'instagram_url' => $this->instagram_url,
            'distance_info' => $this->when(isset($this->distance_info), $this->distance_info),
            'redirection_url' => $this->when(isset($this->redirection_url), $this->redirection_url),
            'branches' => BranchResource::collection($this->whenLoaded('branches')),
        ];
    }
    
}