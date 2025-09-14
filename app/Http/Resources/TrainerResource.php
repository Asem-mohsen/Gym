<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'image' => $this->getUserImageAttribute(),
            'weight' => $this->trainerInformation->weight ?? null,
            'height' => $this->trainerInformation->height ?? null,
            'date_of_birth' => $this->trainerInformation->date_of_birth ?? null,
            'age' => $this->trainerInformation->date_of_birth?->age ?? null,
            'brief_description' => $this->trainerInformation->brief_description ?? null,
            'facebook_url' => $this->trainerInformation->facebook_url ?? null,
            'twitter_url' => $this->trainerInformation->twitter_url ?? null,
            'instagram_url' => $this->trainerInformation->instagram_url ?? null,
            'youtube_url' => $this->trainerInformation->youtube_url ?? null,
        ];
    }
}
