<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'start_date' => $this->formatDate($this->start_date),
            'end_date' => $this->formatDate($this->end_date),
            'status' => $this->status,
            'created_at' => $this->formatDateTime($this->created_at),
            'updated_at' => $this->formatDateTime($this->updated_at),
            
            'membership' => $this->whenLoaded('membership', function () {
                return [
                    'id' => $this->membership->id,
                    'name' => $this->membership->getTranslation('name', app()->getLocale()),
                    'subtitle' => $this->membership->getTranslation('subtitle', app()->getLocale()),
                    'price' => $this->membership->price,
                    'period' => $this->membership->period,
                    'billing_interval' => $this->membership->billing_interval,
                    'description' => $this->membership->getTranslation('general_description', app()->getLocale()),
                ];
            }),
            
            // Branch details
            'branch' => $this->whenLoaded('branch', function () {
                return [
                    'id' => $this->branch->id,
                    'name' => $this->branch->getTranslation('name', app()->getLocale()),
                    'location' => $this->branch->getTranslation('location', app()->getLocale()),
                    'address' => $this->branch->address,
                    'map_url' => $this->branch->map_url,
                ];
            }),
        ];
    }

    /**
     * Format date safely, handling both Carbon instances and strings
     */
    private function formatDate($date): ?string
    {
        if (!$date) {
            return null;
        }

        if (is_string($date)) {
            try {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            } catch (\Exception $e) {
                return $date; // Return as-is if parsing fails
            }
        }

        return $date->format('Y-m-d');
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
