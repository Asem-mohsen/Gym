<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAccountRequest;
use App\Services\AccountService;
use Exception;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function __construct(
        protected AccountService $accountService
    ) {}

    /**
     * Show the account details page
     */
    public function show(): \Illuminate\View\View
    {
        $user = auth()->user()->load('trainerInformation');
        
        return view('admin.account.show', compact('user'));
    }

    /**
     * Update account details
     */
    public function update(UpdateAccountRequest $request)
    {
        try {
            $user = auth()->user();
            $data = $request->validated();
            
            $this->accountService->updateAccount($user, $data);
            
            return redirect()->route('admin.account.show')->with('success', 'Account updated successfully!');
        } catch (Exception $e) {
            Log::error('Failed to update account: ' . $e->getMessage());
            return redirect()->route('admin.account.show')->with('error', 'Failed to update account: ' . $e->getMessage());
        }
    }
}
