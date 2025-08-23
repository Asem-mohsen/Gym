<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingService;
use App\Services\Payments\CashPaymentService;
use Illuminate\Http\Request;
use Exception;

class CashPaymentController extends Controller
{
    protected int $siteSettingId;

    public function __construct(
        protected CashPaymentService $cashPaymentService,
        protected SiteSettingService $siteSettingService
    ) {
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    /**
     * Display the cash payments dashboard (index page for admins)
     */
    public function index(Request $request)
    {
        try {
            $filters = [
                'status' => $request->get('status'),
                'payment_type' => $request->get('payment_type'),
                'search' => $request->get('search'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'branch_id' => $request->get('branch_id'),
            ];

            $data = $this->cashPaymentService->getCashPayments($this->siteSettingId, $filters);
            
            return view('admin.cash-payments.index', [
                'cashPayments' => $data['cashPayments'],
                'statistics' => $data['statistics'],
                'filters' => $filters,
                'branches' => $data['branches'],
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading cash payments: ' . $e->getMessage());
        }
    }

    /**
     * Mark a cash payment as collected
     */
    public function markAsCollected(Request $request)
    {
        try {
            $id = $request->input('id');
            $type = $request->input('type');
            
            if ($type === 'booking') {
                $this->cashPaymentService->markBookingAsCollected($id);
                $message = 'Booking marked as cash collected successfully.';
            } else {
                $this->cashPaymentService->markPaymentAsCollected($id);
                $message = 'Payment marked as cash collected successfully.';
            }

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error updating payment status: ' . $e->getMessage());
        }
    }

    public function markAsPending(Request $request)
    {
        try {
            $id = $request->input('id');
            $type = $request->input('type');
            
            if ($type === 'booking') {
                $this->cashPaymentService->markBookingAsPending($id);
                $message = 'Booking marked as cash pending successfully.';
            } else {
                $this->cashPaymentService->markPaymentAsPending($id);
                $message = 'Payment marked as cash pending successfully.';
            }

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error updating payment status: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $filters = [
                'status' => $request->get('status'),
                'payment_type' => $request->get('payment_type'),
                'search' => $request->get('search'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'branch_id' => $request->get('branch_id'),
            ];

            return $this->cashPaymentService->exportCashPayments($this->siteSettingId, $filters);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    public function getStatistics()
    {
        try {
            $statistics = $this->cashPaymentService->getCashPaymentStatistics($this->siteSettingId);
            return response()->json($statistics);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
