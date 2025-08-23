<?php 
namespace App\Repositories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentRepository
{
    public function getPayments($siteSettingId = null)
    {
        $query = Payment::query();
        
        if ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        }

        $totalPaid = (clone $query)->where('status', 'completed')->sum('amount');
        $totalPending = (clone $query)->where('status', 'pending')->sum('amount');
        $totalFailed = (clone $query)->where('status', 'failed')->sum('amount');

        // Add cash_pending bookings to totalPending
        $cashPendingBookings = Booking::query()
            ->where('status', 'cash_pending')
            ->when($siteSettingId, function($q) use ($siteSettingId) {
                $q->whereHas('branch', function($branchQuery) use ($siteSettingId) {
                    $branchQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->sum('amount');
        
        $totalPending += $cashPendingBookings;

        // Add cash_collected bookings to totalPaid
        $cashCollectedBookings = Booking::query()
            ->where('status', 'cash_collected')
            ->when($siteSettingId, function($q) use ($siteSettingId) {
                $q->whereHas('branch', function($branchQuery) use ($siteSettingId) {
                    $branchQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->sum('amount');
        
        $totalPaid += $cashCollectedBookings;

        $revenueByType = (clone $query)
            ->where('status', 'completed')
            ->select('paymentable_type', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('paymentable_type')
            ->get()
            ->mapWithKeys(function ($item) {
                $type = class_basename($item->paymentable_type);
                return [$type => $item->total_amount];
            });

        // Add cash_collected bookings to revenue by type
        $cashCollectedByType = Booking::query()
            ->where('status', 'cash_collected')
            ->when($siteSettingId, function($q) use ($siteSettingId) {
                $q->whereHas('branch', function($branchQuery) use ($siteSettingId) {
                    $branchQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->select('bookable_type', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('bookable_type')
            ->get();

        // Merge cash collected revenue with existing revenue
        foreach ($cashCollectedByType as $item) {
            $type = class_basename($item->bookable_type);
            $revenueByType[$type] = ($revenueByType[$type] ?? 0) + $item->total_amount;
        }

        // Monthly revenue data for charts
        $monthlyRevenue = (clone $query)
            ->where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return $item->total_amount;
            });

        // Add cash_collected bookings to monthly revenue
        $cashCollectedMonthly = Booking::query()
            ->where('status', 'cash_collected')
            ->whereYear('created_at', date('Y'))
            ->when($siteSettingId, function($q) use ($siteSettingId) {
                $q->whereHas('branch', function($branchQuery) use ($siteSettingId) {
                    $branchQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Merge cash collected monthly revenue
        foreach ($cashCollectedMonthly as $item) {
            $month = $item->month;
            $monthlyRevenue[$month] = ($monthlyRevenue[$month] ?? 0) + $item->total_amount;
        }

        // Payment method statistics
        $paymentMethods = (clone $query)
            ->where('status', 'completed')
            ->select(
                DB::raw('CASE 
                    WHEN paymob_payment_key IS NOT NULL THEN "Card"
                    WHEN payment_method = "cash" THEN "Cash"
                    ELSE "Online"
                END as payment_method'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy(
                DB::raw('CASE 
                    WHEN paymob_payment_key IS NOT NULL THEN "Card"
                    WHEN payment_method = "cash" THEN "Cash"
                    ELSE "Online"
                END')
            )
            ->get();

        // Add cash_collected bookings to payment methods
        $cashCollectedCount = Booking::query()
            ->where('status', 'cash_collected')
            ->when($siteSettingId, function($q) use ($siteSettingId) {
                $q->whereHas('branch', function($branchQuery) use ($siteSettingId) {
                    $branchQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->count();

        $cashCollectedAmount = Booking::query()
            ->where('status', 'cash_collected')
            ->when($siteSettingId, function($q) use ($siteSettingId) {
                $q->whereHas('branch', function($branchQuery) use ($siteSettingId) {
                    $branchQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->sum('amount');

        // Update or add cash payment method
        $cashMethodFound = false;
        foreach ($paymentMethods as $method) {
            if ($method->payment_method === 'Cash') {
                $method->count += $cashCollectedCount;
                $method->total_amount += $cashCollectedAmount;
                $cashMethodFound = true;
                break;
            }
        }

        if (!$cashMethodFound && $cashCollectedCount > 0) {
            $paymentMethods->push((object) [
                'payment_method' => 'Cash',
                'count' => $cashCollectedCount,
                'total_amount' => $cashCollectedAmount
            ]);
        }

        // Recent transactions with status
        $newestTransactions = (clone $query)
            ->with(['user', 'paymentable'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Add recent cash_collected bookings
        $recentCashBookings = Booking::query()
            ->with(['user', 'bookable'])
            ->where('status', 'cash_collected')
            ->when($siteSettingId, function($q) use ($siteSettingId) {
                $q->whereHas('branch', function($branchQuery) use ($siteSettingId) {
                    $branchQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function($booking) {
                return (object) [
                    'id' => $booking->id,
                    'user' => $booking->user,
                    'paymentable' => $booking->bookable,
                    'paymentable_type' => $booking->bookable_type,
                    'paymentable_id' => $booking->bookable_id,
                    'amount' => $booking->amount,
                    'status' => 'completed',
                    'payment_method' => 'Cash',
                    'created_at' => $booking->created_at,
                    'is_cash_booking' => true
                ];
            });

        // Add recent cash_pending bookings
        $recentCashPendingBookings = Booking::query()
            ->with(['user', 'bookable'])
            ->where('status', 'cash_pending')
            ->when($siteSettingId, function($q) use ($siteSettingId) {
                $q->whereHas('branch', function($branchQuery) use ($siteSettingId) {
                    $branchQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function($booking) {
                return (object) [
                    'id' => $booking->id,
                    'user' => $booking->user,
                    'paymentable' => $booking->bookable,
                    'paymentable_type' => $booking->bookable_type,
                    'paymentable_id' => $booking->bookable_id,
                    'amount' => $booking->amount,
                    'status' => 'pending',
                    'payment_method' => 'Cash',
                    'created_at' => $booking->created_at,
                    'is_cash_booking' => true
                ];
            });

        // Merge and sort transactions
        $allTransactions = $newestTransactions->concat($recentCashBookings)->concat($recentCashPendingBookings);
        $newestTransactions = $allTransactions->sortByDesc('created_at')->take(10);

        // Failed transactions
        $failedTransactions = (clone $query)
            ->with(['user', 'paymentable'])
            ->where('status', 'failed')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return [
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'total_failed' => $totalFailed,
            'total_outcome' => 0,
            'revenue_by_type' => $revenueByType,
            'monthly_revenue' => $monthlyRevenue,
            'payment_methods' => $paymentMethods,
            'newest_transactions' => $newestTransactions,
            'failed_transactions' => $failedTransactions,
            'oldest_transactions' => (clone $query)->with(['user', 'paymentable'])->orderBy('created_at')->take(5)->get(),
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
            'branch_id'        => $data['branch_id'] ?? null,
            'site_setting_id'  => $data['site_setting_id'] ?? null,
            'status'           => $data['status'] ?? 'completed',
            'currency'         => $data['currency'] ?? 'EGP',
            'paymob_order_id'  => $data['paymob_order_id'] ?? null,
            'paymob_payment_key' => $data['paymob_payment_key'] ?? null,
        ]);
    }

    public function updatePayment(Payment $payment, Model $paymentable, array $data)
    {
        return $payment->update([
            'user_id'  => $data['user_id'] ?? $payment->user_id,
            'paymentable_type' => get_class($paymentable),
            'paymentable_id'   => $paymentable->id,
            'amount'   => $data['amount'] ?? $payment->amount,
            'offer_id' => $data['offer_id'] ?? $payment->offer_id,
            'branch_id' => $data['branch_id'] ?? $payment->branch_id,
            'status'   => $data['status'] ?? $payment->status,
            'currency' => $data['currency'] ?? $payment->currency,
            'paymob_order_id' => $data['paymob_order_id'] ?? $payment->paymob_order_id,
            'paymob_payment_key' => $data['paymob_payment_key'] ?? $payment->paymob_payment_key,
            'paymob_transaction_id' => $data['paymob_transaction_id'] ?? $payment->paymob_transaction_id,
            'completed_at' => $data['completed_at'] ?? $payment->completed_at,
            'failed_at' => $data['failed_at'] ?? $payment->failed_at,
        ]);
    }
}