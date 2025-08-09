<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactRequest;
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

    public function index(SiteSetting $gym)
    {
        $data = [
            'gym' => $gym,
        ];

        return successResponse($data, 'Contact data retrieved successfully');
    }
    
    public function sendMessage(ContactRequest $request, SiteSetting $gym)
    {
        try {
            $validatedData = $request->validated();

            $contact = $this->contactService->storeContact($validatedData, $gym->id);
            
            return successResponse(compact('contact'), 'Contact message sent successfully');
        } catch (Exception $e) {
            return failureResponse('Error sending contact message, please try again.');
        }
    }
}
