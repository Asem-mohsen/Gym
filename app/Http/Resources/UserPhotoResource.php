<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPhotoResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'is_public' => $this->is_public,
            'sort_order' => $this->sort_order,
            'created_at' => $this->formatDateTime($this->created_at),
            'media' => [
                'original' => $this->getFirstMediaUrl('user_photos'),
            ],
        ];
    }

    /**
     * Format datetime safely, handling both Carbon instances and strings
     */
    private function formatDateTime($dateTime): ?string
    {
        if (!$dateTime) {
            return null;
        }

        if (is_string($dateTime)) {
            try {
                return \Carbon\Carbon::parse($dateTime)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return $dateTime; // Return as-is if parsing fails
            }
        }

        return $dateTime->format('Y-m-d H:i:s');
    }
}
