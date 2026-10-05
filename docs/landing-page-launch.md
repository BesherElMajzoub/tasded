# دليل إطلاق صفحة أصل القمة

## التشغيل والنشر

```powershell
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan optimize
```

يجب ضبط `APP_URL` على النطاق النهائي، وتفعيل HTTPS، وتعطيل `APP_DEBUG` في الإنتاج. المسار الإعلاني المقترح هو `/sadad-alqorood`.

## البيانات المطلوبة قبل الإطلاق

هذه عوائق نشر فعلية، ولا ينبغي بدء إعلانات Google قبل اعتمادها:

- الاسم القانوني للجهة.
- العنوان الفعلي الكامل.
- رقم السجل التجاري والترخيص إن كان منطبقًا.
- جدول الرسوم والتكاليف وتوقيتها وشروط الاسترداد.
- اعتماد تسلسل خطوات العمل والفئات المستهدفة.
- مراجعة قانونية لسياسة الخصوصية والشروط.
- تحديد ما إذا كانت إفصاحات قروض شخصية إضافية منطبقة.
- معرفات القياس المعتمدة وآلية موافقة التتبع.

تُضبط البيانات عبر متغيرات `.env` الموثقة في `.env.example`.

## التتبع

الأحداث المنفذة:

- `whatsapp_click`: نقر رابط واتساب، وليس محادثة مؤكدة.
- `phone_click`: نقر رابط الهاتف، وليس مكالمة مكتملة.
- `faq_interaction`: فتح إجابة في الأسئلة الشائعة.
- `page_view`: يرسله GA4 تلقائيًا عند تفعيل معرفه، أو يُضبط عبر GTM.

`qualified_lead` و`converted_lead` يحتاجان إلى ربط CRM أو إدخال تحويلات غير متصلة؛ لم يتم افتراضهما من النقرات.

يمكن اختبار الإسناد بالرابط:

```text
/sadad-alqorood?utm_source=google&utm_medium=cpc&utm_campaign=sadad_test&gclid=test-click-id
```

تُقرأ فقط معاملات `gclid`, `gbraid`, `wbraid`, و`utm_*` المحددة، وتُحفظ في `sessionStorage` لجلسة المتصفح فقط.

## صفحات المجموعات الإعلانية

كل مجموعة إعلانية توجَّه لصفحة يطابق عنوانها كلماتها المفتاحية (تحسين Quality Score):

- `/sadad-alqorood/riyadh` و`/sadad-alqorood/jeddah`.
- تُضاف صفحات جديدة من `variants` في `config/landing.php` دون تعديل الكود.

## التحويلات غير المتصلة (Offline Conversions)

كل نقرة واتساب تضيف للرسالة سطر `رقم المرجع: XXXXXX`، وتُحفظ النقرة مع `gclid` و`utm_*` في جدول `contact_clicks`.
عندما يتبيّن أن المحادثة عميل جاد، صدّر أرقام المراجع:

```powershell
php artisan landing:export-conversions AB12CD XY34ZW --value=500
```

ثم ارفع ملف CSV الناتج في Google Ads: الأهداف ← التحويلات ← التحميلات. يجب إنشاء إجراء تحويل من نوع Import باسم مطابق لـ `GOOGLE_ADS_OFFLINE_CONVERSION_NAME`، ويُفضّل استخدامه كهدف أساسي للمزايدة بدل نقرة واتساب.

## مؤشرات الأداء

تُراجع أسبوعيًا: الجلسات الإعلانية، توزيع الأجهزة، نسبة نقر واتساب، نسبة نقر الهاتف، العملاء المؤهلون، نسبة التأهيل، معدل التحويل، CPQL، وCore Web Vitals. تُراجع CPC وCost Per Conversion وImpression Rate من Google Ads وليس من كود الصفحة.

## الفحص قبل النشر

```powershell
php artisan test --compact
npm run build
php artisan route:list --except-vendor
```

بعد النشر يجب اختبار HTTPS والحالة 200، وعدم حجب AdsBot، والروابط، وTag Assistant، وLighthouse Mobile/Desktop، ثم متابعة بيانات CrUX الفعلية عند توفرها.
