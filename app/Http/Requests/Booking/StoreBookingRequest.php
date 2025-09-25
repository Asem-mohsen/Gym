<?php
namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check() || Auth::guard('sanctum')->check();
    }

    public function rules(): array
    {
        return [
            'bookable_type' => 'required|string|in:service,class,membership',
            'bookable_id'   => 'required|integer',
            'pricing_id'    => 'required_if:bookable_type,class|integer',
            'schedule_id'   => 'required_if:bookable_type,class|integer',
            'branch_id'     => 'nullable|integer|exists:branches,id',
            'booking_date'  => 'nullable|date',
            'method'        => 'required|string|in:card,wallet,kiosk,cash,paypal,credit_card,debit_card',
            'is_free'       => 'nullable|boolean',
        ];
    }
}
