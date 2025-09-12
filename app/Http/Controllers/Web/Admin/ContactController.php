<?php

namespace App\Http\Controllers\Web\Admin;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of contact messages for the admin's site.
     */
    public function index()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $siteSetting = $user->getCurrentSite();
        
        if (!$siteSetting) {
            return redirect()->route('admin.dashboard')->with('error', 'No site settings found for this admin.');
        }

        $contacts = $this->contactService->getContactsBySiteSetting($siteSetting->id);
        
        return view('admin.contacts.index', compact('contacts', 'siteSetting'));
    }

    /**
     * Mark a contact message as answered.
     */
    public function markAsAnswered(Request $request, $id)
    {
        try {
            $this->contactService->markAsAnswered($id);
            return response()->json(['success' => true, 'message' => 'Contact marked as answered successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error marking contact as answered'], 500);
        }
    }
}
