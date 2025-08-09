<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ContactService;
use App\Http\Requests\Contact\ContactRequest;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index(SiteSetting $siteSetting)
    {
        return view('user.contact' , compact('siteSetting'));
    }

    public function store(ContactRequest $request, SiteSetting $siteSetting)
    {
        $this->contactService->storeContact($request->validated(), $siteSetting->id);
        return redirect()->route('user.contact', ['siteSetting' => $siteSetting->slug])->with('success', 'Contact message sent successfully');
    }
}
