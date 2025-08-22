<?php
namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'schedule_id' => 'nullable|exists:class_schedules,id',
            'booking_date' => 'nullable|date|after_or_equal:today',
            'payment_method' => 'required_if:booking_type,paid_booking|in:cash,card',
        ];
    }
}
