<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار نجاح أي زائر في إرسال رسالة تواصل
     */
    public function test_anyone_can_send_contact_message()
    {
        // تجهيز البيانات المطابقة تماماً للحقول الفعلية في جدولك
        $payload = [
            'name'    => 'Fayez Emad',
            'email'   => 'fayez@example.com',
            'phone'   => '+966500000000', // اختياري
            'message' => 'Hello KMW team, I need a quotation for premium marble slices.',
        ];

        $response = $this->postJson('/api/v1/contact', $payload);

        // التأكد من الرد بحالة 201 والـ JSON الصحيح
        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Your message has been sent successfully.'
                 ]);

        // التأكد من حفظ الرسالة في الجدول الصحيح contact_messages
        $this->assertDatabaseHas('contact_messages', [
            'email'   => 'fayez@example.com',
            'message' => 'Hello KMW team, I need a quotation for premium marble slices.',
            'is_read' => false // القيمة الافتراضية للرسائل الجديدة
        ]);
    }

    /**
     * اختبار فشل الإرسال في حال غياب الحقول الإجبارية
     */
    public function test_contact_message_validation_fails_if_fields_are_missing()
    {
        $payload = [];

        $response = $this->postJson('/api/v1/contact', $payload);

        // التأكد من رجوع خطأ التحقق 422 للحقول المطلوبة فقط
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'message']);
    }
}
