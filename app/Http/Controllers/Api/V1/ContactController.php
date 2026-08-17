<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreContactRequest;
use App\Models\Contact;
use App\Services\V1\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    // جلب كل الرسائل للـ Admin Dashboard (GET /v1/contact)
    public function index(): JsonResponse
    {
        $messages = Contact::latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $messages
        ], 200);
    }

    // إرسال رسالة من زائر الموقع (POST /v1/contact)
    public function store(StoreContactRequest $request): JsonResponse
    {
        $this->contactService->storeMessage($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully.'
        ], 201);
    }

    // تبديل حالة القراءة (PATCH /v1/contact/{id}/toggle-read)
    public function toggleRead($id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => !$contact->is_read]);

        return response()->json([
            'success' => true,
            'is_read' => $contact->is_read
        ], 200);
    }

    // حذف رسالة (DELETE /v1/contact/{id})
    public function destroy($id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully.'
        ], 200);
    }
}
