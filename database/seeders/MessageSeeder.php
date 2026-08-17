<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $messages = [];

        // --- 25 رسالة باللغة العربية ---
        $arabicContents = [
            'استفسار عن أسعار وأحجام ألواح رخام الكرارا الإيطالي لفيلا خاصة.',
            'نبحث عن مورد رخام معتمد لمشروع مجمع سكني في الرياض، يرجى تزويدنا بالكتالوج.',
            'هل يتوفر لديكم رخام كلكتا جولد بدرجة ممتازة وكم هو سعر المتر المربع؟',
            'طلب عرض سعر لتوريد وتركيب أرضيات رخام ترافرتين لمكتب شركة.',
            'نأمل إرسال عينات من الرخام الأسود الأسباني لأحد المشاريع التجارية.',
            'استفسار بخصوص تقطيع الرخام بحسب قياسات وتصاميم خاصة للمخططات الهندسية.',
            'أبحث عن رخام نيرو ماركينا مناسب لمغاسل وحمامات الفنادق.',
            'نرغب في الاتفاق على توريد رخام روزا برتغالي للمشروع القادم، كيف يمكن التواصل؟',
            'هل يوجد لديكم خصومات للكميات الكبيرة من ألواح الرخام الطبيعي؟',
            'طلب تفاصيل تكاليف الشحن والتوصيل لمدينة جُدة لمشروع بناء فيلا.',
            'استفسار عن إمكانية جلي وتلميع الرخام قبل التسليم النهائي.',
            'نود الاستفسار عن مدى تحمل رخام العماني للظروف الجوية الخارجية.',
            'هل يتوفر رخام أبيض يوناني (تاسوس) بداخل المستودعات حالياً؟',
            'طلب تسعيرة شاملة لتشطيب صالة عرض سيارات برخام فاخر.',
            'نبحث عن شريك توريد رخام طبيعي لمجموعة من المشاريع الفندقية.',
            'استفسار عن مدة التوريد المتوقعة لطلب بقيمة 500 متر مربع.',
            'نرغب في زيارة المعرض الخاص بكم للاطلاع على أنواع الرخام المتوفرة.',
            'طلب مواصفات فنية واختبارات الجودة لرخام البيج التركي.',
            'هل يقدم المصنع خدمة التقطيع بالواترجيت للأشكال والديكورات المعقدة؟',
            'استفسار عن أسعار درج الرخام المصنع مقدماً بأبعاد قياسية.',
            'برجاء إرسال تفاصيل الضمان المقدم على تشققات أو عيوب الرخام الطبيعي.',
            'نحتاج لكميات كبيرة من ألواح الجرانيت والرخام للمشروع الاستشاري الحالي.',
            'طلب تحديد موعد لاجتماع لمناقشة أسعار التوريد لمشروع أبراج سكنية.',
            'هل تتوفر لديكم ألواح رخام سمك 3 سم بدلاً من 2 سم؟',
            'استفسار عن طرق العناية بالرخام الطبيعي ومواد الحماية الموصى بها.'
        ];

        $arabicNames = [
            'أحمد العتيبي', 'محمد الشمري', 'سارة السعيد', 'خالد الغامدي', 'فهد الدوسري',
            'عمر القحطاني', 'فاطمة الزهراني', 'عبدالله المالكي', 'ريم الحارثي', 'علي الشهراني',
            'ياسر المطيري', 'منى التميمي', 'سعود العنزي', 'عبدالرحمن الشهري', 'أسماء الحربي',
            'حمزة بن علي', 'نورة السبيعي', 'وليد الغامدي', 'طارق العمري', 'إبراهيم بن سعيد',
            'شهد القاسم', 'حسن البقمي', 'بدر العجمي', 'سحر العلي', 'أيمن الكثيري'
        ];

        // --- 25 Messages in English ---
        $englishContents = [
            'Inquiring about Carrara marble slab pricing and export options for a commercial project.',
            'We need a comprehensive catalog of your Italian marble selections for interior projects.',
            'Requesting quotation for 300 square meters of Calacatta Gold marble tiles.',
            'Do you offer custom cut-to-size marble processing based on architectural blueprints?',
            'Interested in wholesale supply of Travertine stone for a luxury residential complex.',
            'Looking for Nero Marquina marble slabs for high-end hotel bathroom vanities.',
            'Please provide shipping lead times and freight estimates to Dubai Port.',
            'We are looking for a reliable natural stone supplier for long-term partnership.',
            'Requesting technical data sheets and water absorption tests for Greek white marble.',
            'Inquiry regarding discounts on bulk orders exceeding 1,000 square meters.',
            'Can you send physically polished marble samples to our design office in London?',
            'We need Crema Marfil marble slabs with a thickness of 30mm for exterior facades.',
            'Looking for price list and availability for Emperador Dark marble slabs.',
            'Requesting a meeting to discuss procurement details for our upcoming tower project.',
            'Do you provide waterjet cutting services for intricate marble flooring patterns?',
            'Inquiring about surface treatment options (honed, polished, brushed) for beige marble.',
            'We require marble stairs with customized edge profiling for a private villa.',
            'Is there any warranty provided against natural vein structural weaknesses?',
            'Requesting quotation for polished Statuario marble for a hotel lobby display.',
            'What is the estimated production turnaround time for a 500 sqm customized order?',
            'Interested in visiting your factory/warehouse next week to inspect block quality.',
            'Looking for high-density natural quartzite and marble for kitchen countertops.',
            'Please send us your latest brochure and corporate credentials for vendor registration.',
            'Inquiry regarding stone sealing solutions before international shipment.',
            'Do you supply book-matched marble slabs for feature wall applications?'
        ];

        $englishNames = [
            'John Smith', 'Emily Davis', 'Michael Brown', 'Sophia Wilson', 'James Taylor',
            'David Anderson', 'Emma Thomas', 'Daniel Jackson', 'Olivia White', 'Matthew Harris',
            'Lucas Martin', 'Ava Thompson', 'Alexander Garcia', 'Isabella Martinez', 'Ethan Robinson',
            'Liam Clark', 'Charlotte Rodriguez', 'Benjamin Lewis', 'Amelia Lee', 'Henry Walker',
            'Sebastian Hall', 'Harper Allen', 'Jack Young', 'Ella King', 'Oliver Wright'
        ];

        // توليد الـ 25 رسالة العربية
        for ($i = 0; $i < 25; $i++) {
            $isRead = (bool)rand(0, 1);
            $randomHours = rand(1, 720);
            $createdAt = Carbon::now()->subHours($randomHours);

            $messages[] = [
                'name'       => $arabicNames[$i],
                'email'      => 'client.ar' . ($i + 1) . '@example.com',
                'phone'      => '+966 5' . rand(10000000, 99999999),
                'message'    => $arabicContents[$i],
                'is_read'    => $isRead,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        // توليد الـ 25 رسالة الإنجليزية
        for ($i = 0; $i < 25; $i++) {
            $isRead = (bool)rand(0, 1);
            $randomHours = rand(1, 720);
            $createdAt = Carbon::now()->subHours($randomHours);

            $messages[] = [
                'name'       => $englishNames[$i],
                'email'      => 'client.en' . ($i + 1) . '@example.com',
                'phone'      => '+1 (555) ' . rand(100, 999) . '-' . rand(1000, 9999),
                'message'    => $englishContents[$i],
                'is_read'    => $isRead,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        // إدخال البيانات مباشرة في جدول contact_messages
        DB::table('contact_messages')->insert($messages);
    }
}
