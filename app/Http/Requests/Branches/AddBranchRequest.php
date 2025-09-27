<?php

namespace App\Http\Requests\Branches;

use Illuminate\Foundation\Http\FormRequest;

class AddBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_branches');
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $openingHours = $this->input('opening_hours', []);
        
        foreach ($openingHours as $index => $hoursData) {
            // Handle is_closed field - convert array to single value if needed
            if (isset($hoursData['is_closed'])) {
                if (is_array($hoursData['is_closed'])) {
                    // If it's an array, take the first value
                    $openingHours[$index]['is_closed'] = !empty($hoursData['is_closed']) ? (bool) $hoursData['is_closed'][0] : false;
                } else {
                    // If it's not an array, convert to boolean
                    $openingHours[$index]['is_closed'] = (bool) $hoursData['is_closed'];
                }
            } else {
                // If not set (unchecked checkbox), set it to false
                $openingHours[$index]['is_closed'] = false;
            }
        }
        
        $this->merge([
            'opening_hours' => $openingHours
        ]);
    }

    public function rules(): array
    {
        return [
            'manager_id'  => ['required',  'exists:users,id'],
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
            'opening_hours.*.opening_time' => ['nullable', 'date_format:H:i'],
            'opening_hours.*.closing_time' => ['nullable', 'date_format:H:i'],
            'opening_hours.*.is_closed' => ['nullable', 'boolean'],
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
                // is_closed is now guaranteed to be a boolean due to prepareForValidation
                $isClosed = (bool) ($hoursData['is_closed'] ?? false);
                
                // If not closed, opening_time and closing_time are required
                if (!$isClosed) {
                    if (empty($hoursData['opening_time'])) {
                        $validator->errors()->add(
                            "opening_hours.{$index}.opening_time",
                            "The opening time is required when the branch is not closed."
                        );
                    }
                    if (empty($hoursData['closing_time'])) {
                        $validator->errors()->add(
                            "opening_hours.{$index}.closing_time",
                            "The closing time is required when the branch is not closed."
                        );
                    }
                }
                
                if (!empty($hoursData['opening_time']) && !empty($hoursData['closing_time']) && !$isClosed) {
                    $openingTime = $hoursData['opening_time'];
                    $closingTime = $hoursData['closing_time'];
                    
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
