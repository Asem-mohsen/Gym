<?php

namespace App\Services\Payments;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Branch;
use App\Repositories\CashPaymentRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashPaymentService
{
    public function __construct(
        protected CashPaymentRepository $cashPaymentRepository
    ) {
        $this->cashPaymentRepository = $cashPaymentRepository;
    }

    /**
     * Get cash payments with filters
     */
    public function getCashPayments(int $siteSettingId, array $filters = [])
    {
        $cashPayments = $this->cashPaymentRepository->getCashPayments($siteSettingId, $filters);
        $statistics = $this->getCashPaymentStatistics($siteSettingId);
        $branches = Branch::where('site_setting_id', $siteSettingId)->get();

        return [
            'cashPayments' => $cashPayments,
            'statistics' => $statistics,
            'branches' => $branches,
        ];
    }

    /**
     * Get cash payment statistics
     */
    public function getCashPaymentStatistics(int $siteSettingId): array
    {
        // Cash pending bookings
        $cashPendingBookings = Booking::where('status', 'cash_pending')
            ->whereHas('branch', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->sum('amount');

        // Cash collected bookings
        $cashCollectedBookings = Booking::where('status', 'cash_collected')
            ->whereHas('branch', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->sum('amount');

        // Cash pending payments
        $cashPendingPayments = Payment::where('status', 'pending')
            ->where('payment_method', 'cash')
            ->where('site_setting_id', $siteSettingId)
            ->sum('amount');

        // Cash completed payments
        $cashCompletedPayments = Payment::where('status', 'completed')
            ->where('payment_method', 'cash')
            ->where('site_setting_id', $siteSettingId)
            ->sum('amount');

        // Total cash pending
        $totalCashPending = $cashPendingBookings + $cashPendingPayments;

        // Total cash collected
        $totalCashCollected = $cashCollectedBookings + $cashCompletedPayments;

        // Count of pending items
        $pendingCount = Booking::where('status', 'cash_pending')
            ->whereHas('branch', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->count() + Payment::where('status', 'pending')
            ->where('payment_method', 'cash')
            ->where('site_setting_id', $siteSettingId)
            ->count();

        // Count of collected items
        $collectedCount = Booking::where('status', 'cash_collected')
            ->whereHas('branch', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->count() + Payment::where('status', 'completed')
            ->where('payment_method', 'cash')
            ->where('site_setting_id', $siteSettingId)
            ->count();

        return [
            'total_cash_pending' => $totalCashPending,
            'total_cash_collected' => $totalCashCollected,
            'cash_pending_bookings' => $cashPendingBookings,
            'cash_collected_bookings' => $cashCollectedBookings,
            'cash_pending_payments' => $cashPendingPayments,
            'cash_completed_payments' => $cashCompletedPayments,
            'pending_count' => $pendingCount,
            'collected_count' => $collectedCount,
        ];
    }

    /**
     * Mark booking as cash collected
     */
    public function markBookingAsCollected(int $bookingId): void
    {
        DB::transaction(function () use ($bookingId) {
            $booking = Booking::findOrFail($bookingId);
            $booking->update(['status' => 'cash_collected']);
        });
    }

    /**
     * Mark booking as cash pending
     */
    public function markBookingAsPending(int $bookingId): void
    {
        DB::transaction(function () use ($bookingId) {
            $booking = Booking::findOrFail($bookingId);
            $booking->update(['status' => 'cash_pending']);
        });
    }

    /**
     * Mark payment as cash collected
     */
    public function markPaymentAsCollected(int $paymentId): void
    {
        DB::transaction(function () use ($paymentId) {
            $payment = Payment::findOrFail($paymentId);
            $payment->update([
                'status' => 'completed',
                'payment_method' => 'cash',
                'completed_at' => now(),
            ]);
        });
    }

    /**
     * Mark payment as cash pending
     */
    public function markPaymentAsPending(int $paymentId): void
    {
        DB::transaction(function () use ($paymentId) {
            $payment = Payment::findOrFail($paymentId);
            $payment->update([
                'status' => 'pending',
                'payment_method' => 'cash',
            ]);
        });
    }

    /**
     * Export cash payments data
     */
    public function exportCashPayments(int $siteSettingId, array $filters = [])
    {
        return $this->cashPaymentRepository->exportCashPayments($siteSettingId, $filters);
    }
}
