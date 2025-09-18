<?php

namespace App\Http\Requests\Branches;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_branches');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'manager_id'  => ['required' ,  'exists:users,id'],
            'name.en'     => ['required' , 'max:255', 'string'],
            'name.ar'     => ['required' , 'max:255', 'string'],
            'location.en' => ['required' , 'max:1000'],
            'location.ar' => ['required' , 'max:1000'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
            'city'        => ['nullable', 'max:255'],
            'region'      => ['nullable', 'max:255'],
            'country'     => ['nullable', 'max:255'],
            'type'        => ['required' , 'in:mix,men,ladies'],
            'size'        => ['required'],
            'facebook_url'=> ['nullable', 'url'],
            'x_url'       => ['nullable', 'url'],
            'instagram_url'=>['nullable', 'url'],
            'map_url'     => ['nullable', 'url'],
            'is_visible'  => ['nullable', 'boolean'],
            'phones'       =>['required', 'array' , 'min:1'],
            'opening_hours' => ['nullable', 'array'],
            'opening_hours.*.days' => ['required_with:opening_hours', 'array', 'min:1'],
            'opening_hours.*.days.*' => ['in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'opening_hours.*.opening_time' => ['nullable', 'date_format:H:i', 'required_without:opening_hours.*.is_closed'],
            'opening_hours.*.closing_time' => ['nullable', 'date_format:H:i', 'required_without:opening_hours.*.is_closed'],
            'opening_hours.*.is_closed' => ['nullable', 'in:0,1,true,false'],
            'main_image'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:3072'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $openingHours = $this->input('opening_hours', []);
            
            foreach ($openingHours as $index => $hoursData) {
                if (!empty($hoursData['opening_time']) && !empty($hoursData['closing_time']) && !($hoursData['is_closed'] ?? false)) {
                    $openingTime = $hoursData['opening_time'];
                    $closingTime = $hoursData['closing_time'];
                    
                    // Convert to 24-hour format for comparison
                    $openingMinutes = $this->timeToMinutes($openingTime);
                    $closingMinutes = $this->timeToMinutes($closingTime);
                    
                    if ($closingMinutes < $openingMinutes) {
                        $diff = $openingMinutes - $closingMinutes;
                        if ($diff > 720) { // 12 hours = 720 minutes
                            $validator->errors()->add(
                                "opening_hours.{$index}.closing_time",
                                "The closing time must be after the opening time or on the next day."
                            );
                        }
                    }
                }
            }
        });
    }

    /**
     * Convert time string to minutes since midnight
     */
    private function timeToMinutes($time)
    {
        $parts = explode(':', $time);
        return ($parts[0] * 60) + $parts[1];
    }
}
