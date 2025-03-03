<?php 
namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
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
    
    public function createPayment(Model $paymentable, array $data)
    {
        return Payment::create([
            'user_id'          => $data['user_id'],
            'paymentable_type' => get_class($paymentable),
            'paymentable_id'   => $paymentable->id,
            'amount'           => $data['amount'] ?? 0,
            'offer_id'         => $data['offer_id'] ?? null,
            'status'           => 'completed',
        ]);
    }


    public function updatePayment(Payment $payment, Model $paymentable, array $data)
    {
        return $payment->update([
            'user_id'  => $data['user_id'],
            'paymentable_type' => get_class($paymentable),
            'paymentable_id'   => $paymentable->id,
            'amount'   => $data['amount'] ?? $payment->amount,
            'offer_id' => $data['offer_id'] ?? $payment->offer_id,
            'status'   => $payment->status,
        ]);
    }

    public function deletePayment(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $payment->delete();
        });
    }

    public function findById(int $id)
    {
        return Payment::findOrFail($id);
    }

}