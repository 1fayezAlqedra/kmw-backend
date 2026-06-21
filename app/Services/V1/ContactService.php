<?php

namespace App\Services\V1;

use App\Models\Contact;

class ContactService
{
    public function storeMessage(array $data): Contact
    {
        // حفظ الرسالة في قاعدة البيانات
        return Contact::create($data);
    }
}
