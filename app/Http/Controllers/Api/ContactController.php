<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * استقبال وحفظ رسائل "اتصل بنا" القادمة من الموقع الأمامي
     */
    public function store(Request $request): JsonResponse
    {
        // التحقق من صحة البيانات المرسلة من فورم الاتصال
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'message' => 'required|string|min:10',
        ]);

        // إذا كان هناك خطأ في البيانات، أرجع الأخطاء فوراً للـ Vue.js لعرضها للمستخدم
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422); // الكود 422 يعني Unprocessable Entity
        }

        // حفظ الرسالة في قاعدة البيانات وتلقائياً حقل is_read سيكون false (غير مقروءة)
        $message = ContactMessage::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully! We will contact you soon.',
            'data'    => $message
        ], 201); // الكود 201 يعني Created بنجاح
    }
}
