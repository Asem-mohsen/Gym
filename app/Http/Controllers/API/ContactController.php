<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Exception;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function contactUs(Request $request, SiteSetting $gym)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
            ]);

            $contact = $this->contactService->storeContact($validatedData, $gym->id);
            
            return successResponse(compact('contact'), 'Contact message sent successfully');
        } catch (Exception $e) {
            return failureResponse('Error sending contact message, please try again.');
        }
    }
}
