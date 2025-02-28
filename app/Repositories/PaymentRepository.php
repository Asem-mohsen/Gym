<?php 
namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentRepository
{
    public function getPayments()
    {
        return [
            'total_paid' => Payment::where('status', 'paid')->sum('amount'),
            'total_pending' => Payment::where('status', 'pending')->sum('amount'),
            'newest_transactions' => Payment::orderByDesc('created_at')->take(5)->get(),
            'oldest_transactions' => Payment::orderBy('created_at')->take(5)->get(),
            'detailed_payments' => Payment::with('paymentable')->get(),
        ];
    }
    

    public function updatePayment(Payment $payment, array $data)
    {

    }

    public function deletePayment(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $payment->delete();
        });
    }

    /**
     * Find a branch by ID with its phones.
     */
    public function findById(int $id)
    {
        return Payment::findOrFail($id);
    }

}