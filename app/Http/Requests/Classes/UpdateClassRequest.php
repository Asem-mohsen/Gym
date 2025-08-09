<?php

namespace App\Http\Requests\Classes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:classes,slug,' . $this->route('class')->id,
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'trainers' => 'required|array',
            'trainers.*' => 'exists:users,id',
            'schedules' => 'nullable|array',
            'schedules.*.day' => 'required|string|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'image' => 'nullable|max:2048',
            'pricings' => 'nullable|array',
            'pricings.*.price' => 'required|numeric|min:0',
            'pricings.*.duration' => 'required|string|max:255',
        ];
    }
} 