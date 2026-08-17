<?php

namespace App\Services\V1;

use App\Models\Contact;

class ContactService
{
    /**
     * Store new visitor contact message.
     */
    public function storeMessage(array $data): Contact
    {
        return Contact::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'],
            'is_read' => false,
        ]);
    }
}
