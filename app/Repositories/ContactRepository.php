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
} 