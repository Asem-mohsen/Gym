<?php

namespace App\Services;

use App\Repositories\ContactRepository;
use App\Models\Contact;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function storeContact(array $data, int $siteSettingId): Contact
    {
        $data['site_setting_id'] = $siteSettingId;
        return $this->contactRepository->create($data);
    }
} 