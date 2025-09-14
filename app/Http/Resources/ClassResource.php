<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
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
            'description' => $this->description,
            'type' => $this->type,
            'max_participants' => $this->max_participants ?? 20,
            'status' => $this->status,
            'image' => $this->getFirstMediaUrl('class_images'),
            'trainers' => TrainerResource::collection($this->trainers),
            'schedules' => ClassScheduleResource::collection($this->schedules),
            'pricings' => ClassPricingResource::collection($this->pricings),
            'branches' => BranchResource::collection($this->branches),
        ];
    }
    
}
