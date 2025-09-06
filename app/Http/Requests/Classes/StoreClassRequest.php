<?php

namespace App\Http\Requests\Classes;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'image' => 'required|max:2048',
            'trainers' => 'required|array',
            'trainers.*' => 'exists:users,id',
            'branch_ids' => 'required|array',
            'branch_ids.*' => 'exists:branches,id',
            'schedules' => 'required|array',
            'schedules.*.day' => 'required|string|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'pricings' => 'required|array',
            'pricings.*.price' => 'required|numeric|min:0',
            'pricings.*.duration' => 'required|string|max:255',
        ];
    }
} 