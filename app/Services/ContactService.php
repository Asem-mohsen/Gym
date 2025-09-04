<?php

namespace App\Services;

use App\Repositories\ContactRepository;
use App\Models\{Contact, SiteSetting};
use App\Services\NotificationService;

class ContactService
{
    protected $contactRepository;
    protected $notificationService;

    public function __construct(ContactRepository $contactRepository, NotificationService $notificationService)
    {
        $this->contactRepository = $contactRepository;
        $this->notificationService = $notificationService;
    }

    public function storeContact(array $data, int $siteSettingId): Contact
    {
        $data['site_setting_id'] = $siteSettingId;
        $contact = $this->contactRepository->create($data);
        
        // Send notification to sales users
        $siteSetting = SiteSetting::find($siteSettingId);
        if ($siteSetting) {
            $this->notificationService->sendContactUsNotification($contact, $siteSetting);
        }
        
        return $contact;
    }

    /**
     * Get all contacts for a specific site setting.
     */
    public function getContactsBySiteSetting(int $siteSettingId)
    {
        return $this->contactRepository->getBySiteSetting($siteSettingId);
    }

    /**
     * Mark a contact message as answered.
     */
    public function markAsAnswered(int $contactId): bool
    {
        return $this->contactRepository->markAsAnswered($contactId);
    }
} 