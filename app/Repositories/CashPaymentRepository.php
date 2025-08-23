<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Payment;

class CashPaymentRepository
{
    /**
     * Get cash payments with filters
     */
    public function getCashPayments(int $siteSettingId, array $filters = [])
    {
        $bookings = $this->getCashBookings($siteSettingId, $filters);
        
        $payments = $this->getCashPaymentsData($siteSettingId, $filters);
        
        $cashPayments = collect([...$bookings, ...$payments])
            ->sortByDesc('created_at')
            ->values();

        return $cashPayments;
    }

    /**
     * Get cash bookings (cash_pending and cash_collected)
     */
    private function getCashBookings(int $siteSettingId, array $filters = [])
    {
        $query = Booking::with(['user', 'branch', 'bookable'])
            ->whereIn('status', ['cash_pending', 'cash_collected'])
            ->whereHas('branch', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            });

        $this->applyFilters($query, $filters);

        return $query->get()->map(function ($booking) {
            return [
                'id' => $booking->id,
                'type' => 'booking',
                'user_name' => $booking->user->name,
                'user_email' => $booking->user->email,
                'user_phone' => $booking->user->phone,
                'branch_name' => $booking->branch->name ?? 'N/A',
                'bookable_type' => class_basename($booking->bookable_type),
                'bookable_name' => $this->getBookableName($booking->bookable),
                'amount' => $booking->amount,
                'status' => $booking->status,
                'payment_method' => 'cash',
                'created_at' => $booking->created_at,
                'updated_at' => $booking->updated_at,
                'booking_date' => $booking->booking_date,
            ];
        });
    }

    /**
     * Get cash payments data
     */
    private function getCashPaymentsData(int $siteSettingId, array $filters = [])
    {
        $query = Payment::with(['user', 'branch', 'paymentable'])
            ->where('payment_method', 'cash')
            ->where('site_setting_id', $siteSettingId);

        $this->applyFilters($query, $filters);

        return $query->get()->map(function ($payment) {
            return [
                'id' => $payment->id,
                'type' => 'payment',
                'user_name' => $payment->user->name,
                'user_email' => $payment->user->email,
                'user_phone' => $payment->user->phone,
                'branch_name' => $payment->branch->name ?? 'N/A',
                'paymentable_type' => class_basename($payment->paymentable_type),
                'paymentable_name' => $this->getPaymentableName($payment->paymentable),
                'amount' => $payment->amount,
                'status' => $payment->status,
                'payment_method' => 'cash',
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
                'completed_at' => $payment->completed_at,
            ];
        });
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, array $filters)
    {
        // Status filter
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'cash_pending') {
                $query->where('status', 'cash_pending');
            } elseif ($filters['status'] === 'cash_collected') {
                $query->where('status', 'cash_collected');
            } elseif ($filters['status'] === 'pending') {
                $query->where('status', 'pending');
            } elseif ($filters['status'] === 'completed') {
                $query->where('status', 'completed');
            }
        }

        // Payment type filter
        if (!empty($filters['payment_type'])) {
            if ($filters['payment_type'] === 'bookings') {
                $query->whereIn('status', ['cash_pending', 'cash_collected']);
            } elseif ($filters['payment_type'] === 'payments') {
                $query->where('payment_method', 'cash');
            }
        }

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Branch filter
        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }
    }

    /**
     * Get bookable name
     */
    private function getBookableName($bookable)
    {
        if (!$bookable) return 'N/A';
        
        if (method_exists($bookable, 'getTranslation')) {
            return $bookable->getTranslation('name', app()->getLocale());
        }
        
        return $bookable->name ?? 'N/A';
    }

    /**
     * Get paymentable name
     */
    private function getPaymentableName($paymentable)
    {
        if (!$paymentable) return 'N/A';
        
        if (method_exists($paymentable, 'getTranslation')) {
            return $paymentable->getTranslation('name', app()->getLocale());
        }
        
        return $paymentable->name ?? 'N/A';
    }

    /**
     * Export cash payments data
     */
    public function exportCashPayments(int $siteSettingId, array $filters = [])
    {
        $cashPayments = $this->getCashPayments($siteSettingId, $filters);
        
        // Create CSV content
        $headers = [
            'ID', 'Type', 'User Name', 'User Email', 'User Phone', 'Branch',
            'Item Type', 'Item Name', 'Amount', 'Status', 'Payment Method',
            'Created Date', 'Updated Date'
        ];

        $csvData = [];
        $csvData[] = $headers;

        foreach ($cashPayments as $payment) {
            $csvData[] = [
                $payment['id'],
                $payment['type'],
                $payment['user_name'],
                $payment['user_email'],
                $payment['user_phone'],
                $payment['branch_name'],
                $payment['paymentable_type'] ?? $payment['bookable_type'],
                $payment['paymentable_name'] ?? $payment['bookable_name'],
                $payment['amount'],
                $payment['status'],
                $payment['payment_method'],
                $payment['created_at']->format('Y-m-d H:i:s'),
                $payment['updated_at']->format('Y-m-d H:i:s'),
            ];
        }

        $filename = 'cash_payments_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response()->streamDownload(function () use ($csvData) {
            $output = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
