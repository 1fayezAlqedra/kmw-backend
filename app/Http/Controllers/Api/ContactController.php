<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Admin CMS: جلب جميع رسائل اتصل بنا الواردة للأدمن لرؤيتها في اللوحة
     */
    public function index(): JsonResponse
    {
        $messages = Contact::latest()->get();
        return response()->json(['success' => true, 'data' => $messages], 200);
    }

    /**
     * Public: استقبال رسالة جديدة من زائر الموقع (مفتوح للعامة)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $contact = Contact::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully. Thank you!'
        ], 201);
    }

    /**
     * Admin CMS: حذف رسالة معينة بعد قراءتها من قبل الأدمن
     */
    public function destroy(string $id): JsonResponse
    {
        $message = Contact::findOrFail($id);
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully.'
        ], 200);
    }
}
