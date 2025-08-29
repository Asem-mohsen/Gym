<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository
{
    /**
     * Store a new contact message.
     *
     * @param array $data
     * @return Contact
     */
    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    /**
     * Get all contacts for a specific site setting.
     *
     * @param int $siteSettingId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBySiteSetting(int $siteSettingId)
    {
        return Contact::where('site_setting_id', $siteSettingId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Mark a contact message as answered.
     *
     * @param int $contactId
     * @return bool
     */
    public function markAsAnswered(int $contactId): bool
    {
        $contact = Contact::findOrFail($contactId);
        return $contact->update(['is_answered' => true]);
    }
} 