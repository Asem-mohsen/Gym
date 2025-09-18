<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'address' => $this->address,
            'gender' => $this->gender,
            'country' => $this->country,
            'city' => $this->city,
            'status' => $this->status ? 'active' : 'inactive',
            'last_visit_at' => $this->formatDateTime($this->last_visit_at),
            
            'profile_image' => $this->user_image,
            
            'public_photos' => UserPhotoResource::collection($this->whenLoaded('publicPhotos')),
            
            'subscriptions' => SubscriptionResource::collection($this->whenLoaded('subscriptions')),
            
            'trainer_information' => $this->whenLoaded('trainerInformation', function () {
                return [
                    'weight' => $this->trainerInformation->weight,
                    'height' => $this->trainerInformation->height,
                    'date_of_birth' => $this->formatDate($this->trainerInformation->date_of_birth),
                    'brief_description' => $this->trainerInformation->brief_description,
                    'facebook_url' => $this->trainerInformation->facebook_url,
                    'twitter_url' => $this->trainerInformation->twitter_url,
                    'instagram_url' => $this->trainerInformation->instagram_url,
                    'youtube_url' => $this->trainerInformation->youtube_url,
                ];
            }),
            
            'bookings' => $this->whenLoaded('bookings', function () {
                return $this->bookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_date' => $this->formatDate($booking->booking_date),
                        'start_time' => $this->formatTime($booking->start_time),
                        'end_time' => $this->formatTime($booking->end_time),
                        'status' => $booking->status,
                        'created_at' => $this->formatDateTime($booking->created_at),
                        'bookable_type' => $booking->bookable_type,
                        'bookable_id' => $booking->bookable_id,
                    ];
                });
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
     * Format time safely, handling both Carbon instances and strings
     */
    private function formatTime($time): ?string
    {
        if (!$time) {
            return null;
        }

        if (is_string($time)) {
            try {
                return \Carbon\Carbon::parse($time)->format('H:i:s');
            } catch (\Exception $e) {
                return $time; // Return as-is if parsing fails
            }
        }

        return $time->format('H:i:s');
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
