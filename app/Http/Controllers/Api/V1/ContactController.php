<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreContactRequest;
use App\Services\V1\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $this->contactService->storeMessage($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully.'
        ], 201);
    }

    // يمكنك إضافة دالة index هنا لجلب الرسائل للأدمن (مع تفعيل صلاحية الـ Admin فقط)
}
