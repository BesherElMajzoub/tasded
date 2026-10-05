# دليل ربط تحويل Qualified Lead والمزايدة الذكية بـ Google Ads

هاد الدليل بيشرح خطوة بخطوة كيف تخلّي Google Ads يزايد على **العميل الحقيقي** مش على أي حدا بينقر. بيبلّش من فكرة التحويلات وبيوصل لـ Target CPA.

> ملاحظة: أسماء القوائم بواجهة Google Ads بتتغير أحياناً. كتبتها بالإنجليزي لأنها هيك بتطلع بأغلب الحسابات. إذا واجهتك عربية، دوّر على نفس المعنى.

---

## 1. الفكرة ببساطة

### شو هو التحويل (Conversion)؟
هو الحدث اللي بتقول لـ Google عنه: «هاد اللي بدي ياه». Google بيستعمله لشغلتين:
1. **يقيس**: كم واحد عمل هالحدث، وقديش كلّفك كل واحد (Cost / conv).
2. **يتعلّم**: بالمزايدة الذكية، Google بيدوّر على ناس بيشبهوا اللي عملوا التحويل، وبيرفع مزايدته عليهن.

### ليش نقرة واتساب مش كفاية؟
نقرة واتساب بتصير قبل المحادثة. كتير ناس بينقروا وما بيبعتوا شي، أو بيبعتوا وما بيكونوا ضمن نطاق الخدمة، أو بيكونوا منافسين. إذا كانت نقرة واتساب هي الهدف، Google بيتعلّم يجيبلك **نقّيرة**، مش عملاء.

### الحل: تحويلين بدورين مختلفين
| التحويل | شو بيقيس | مين بيسجّله | الدور |
|---|---|---|---|
| `whatsapp_click` / `phone_click` | نقرة على زر التواصل | الموقع تلقائياً | **Secondary** (للمراقبة بس) |
| `Qualified Lead` | محادثة طلعت عميل جاد | إنت، برفع ملف | **Primary** (هدف المزايدة) |

### كيف بيعرف Google أنو العميل إجا من إعلان معيّن؟
1. الشخص بينقر الإعلان، وGoogle بيزيد للرابط `?gclid=...`. هاد معرّف فريد **لهالنقرة**.
2. الموقع بيحفظ الـ `gclid` بالمتصفح.
3. لما ينقر واتساب، الموقع:
   - بيضيف للرسالة سطر `رقم المرجع: AB12CD`.
   - بيحفظ بجدول `contact_clicks` إنو المرجع `AB12CD` ← `gclid` هاد.
4. بعد المحادثة، إذا طلع جاد، بتصدّر المرجع لملف CSV فيه الـ `gclid`.
5. بترفع الملف، وGoogle بيربط التحويل بالنقرة الأصلية: الكلمة، الإعلان، الجهاز، الوقت...

هاد اسمه **Offline Conversion Import**.

---

## 2. المتطلبات قبل ما تبلّش

### على الموقع (مرة وحدة)
```powershell
php artisan migrate --force
npm run build
php artisan optimize
```

وبملف `.env` على السيرفر:
```env
APP_URL=https://your-domain.com
APP_DEBUG=false

GA4_ID=G-XXXXXXX
GOOGLE_ADS_ID=AW-XXXXXXXXX
GOOGLE_ADS_WHATSAPP_LABEL=xxxxxxxxxxx
GOOGLE_ADS_PHONE_LABEL=yyyyyyyyyyy
GOOGLE_ADS_OFFLINE_CONVERSION_NAME="Qualified Lead"
```

> **مهم:** إذا عبّيت `GTM_ID`، الموقع بيحمّل Google Tag Manager بدل gtag، وتحويلات النقر **ما بتنبعت تلقائياً**. لازم تعمل الـ Tags يدوياً داخل GTM. للأسهل: خلّي `GTM_ID` فاضي واستعمل `GA4_ID` و `GOOGLE_ADS_ID`.

### بحساب Google Ads
- **Auto-tagging لازم يكون شغّال.** بدونه ما في `gclid` وما في ربط.
  `Admin` ← `Account settings` ← `Auto-tagging` ← ✅ Tag the URL that people click through from my ad.

---

## 3. إنشاء تحويلات النقر (Secondary)

هدول بيعطوك `GOOGLE_ADS_ID` والـ labels.

1. `Goals` ← `Conversions` ← `Summary` ← **+ Create conversion action**.
2. اختار **Website**، حط الدومين، وبعدين **Add a conversion action manually**.
3. الإعدادات:
   - **Goal and action optimization:** `Contact`.
   - **Conversion name:** `WhatsApp Click`.
   - **Value:** Don't use a value.
   - **Count:** **One**. لازم One لأنو نفس الشخص ممكن ينقر أكتر من زر، والموقع أصلاً بيبعت رقم مرجع مختلف لكل نقرة.
   - **Click-through conversion window:** 30 days.
4. احفظ. بيطلعلك كود فيه سطر متل:
   ```js
   gtag('event', 'conversion', {'send_to': 'AW-123456789/AbCdEfGhIjk'});
   ```
   - `AW-123456789` ← `GOOGLE_ADS_ID`
   - `AbCdEfGhIjk` ← `GOOGLE_ADS_WHATSAPP_LABEL`
5. كرّر نفس الشي لـ `Phone Click` ← `GOOGLE_ADS_PHONE_LABEL`.
6. **حوّلهن لـ Secondary.** بس تفتح كل واحد: `Edit settings` ← **Action optimization** ← `Secondary action`.

> ما تنسخ الكود للموقع. الموقع جاهز وبيستعمل القيم من `.env`.

---

## 4. إنشاء تحويل Qualified Lead (Primary)

1. `Goals` ← `Conversions` ← `Summary` ← **+ Create conversion action**.
2. اختار **Import**، وبعدين **CRM, files, or other data sources** (أو Manual import using API or uploads).
3. اختار **Track conversions from clicks** ← Continue.
4. الإعدادات:
   | الإعداد | القيمة | ليش |
   |---|---|---|
   | Goal / category | `Qualified lead` | بيوصف المرحلة بدقة |
   | Conversion name | `Qualified Lead` | **لازم يطابق حرفياً** `GOOGLE_ADS_OFFLINE_CONVERSION_NAME` |
   | Value | Use the same value / أو بدون | شوف القسم 7 |
   | Count | **One** | عميل واحد = تحويل واحد |
   | Click-through window | **90 days** | العميل ممكن يتأخر يقرر |
   | Attribution model | Data-driven | الافتراضي والأفضل |
5. تأكد إنو **Action optimization = Primary action**.

> استنّى حوالي يوم بعد إنشاء التحويل قبل أول رفع. الرفع المبكر ممكن يعطي خطأ «conversion action not found».

---

## 5. ربط الهدف بالحملة

1. افتح الحملة ← `Settings` ← **Goals**.
2. اختار **Use campaign-specific goals** ← خلّي **Qualified lead** بس.
   (أو خلّي Account-default إذا Qualified Lead هو الوحيد اللي Primary بالحساب.)
3. تأكد إنو بعمود **Conversions** بالحملة عم يتسجل Qualified Lead بس، وإنو نقرات واتساب طالعة بعمود **All conv.**

---

## 6. الشغل اليومي: من المحادثة للرفع

### أ. حدّد شو يعني «جاد» (قبل أول يوم)
اكتب معايير ثابتة والتزم فيها. إذا كل يوم بتصنّف بطريقة، Google بيتعلّم غلط. مثال:
- ✅ ساكن بالرياض أو جدة.
- ✅ نوع القرض ضمن نطاق الخدمة (مش قطاع خاص، مش سكني).
- ✅ ردّ وكمّل المحادثة لمرحلة مراجعة الحالة.
- ❌ بس سأل «كم النسبة؟» واختفى.
- ❌ برّا النطاق، أو منافس، أو رقم غلط.

### ب. سجّل رقم المرجع
كل رسالة جاية من الموقع آخرها:
```
رقم المرجع: AB12CD
```
لما الحالة تطابق المعايير، سجّل الرقم (بدفتر، أو Excel، أو تسمية بواتساب Business).

### ج. صدّر الملف (يومياً، أو كل يومين بالكتير)
```powershell
php artisan landing:export-conversions AB12CD XY34ZW QW56ER
```
- بيعلّم المراجع كـ «مؤهلة» بقاعدة البيانات.
- بيطلع ملف بـ `storage/app/private/conversions/conversions-YYYYMMDD-HHMMSS.csv`.
- أي مرجع ما إلو `gclid` (يعني الشخص ما إجا من إعلان Google) بيتجاهله ويطبعلك تحذير. هاد طبيعي.

شكل الملف:
```csv
Parameters:TimeZone=Asia/Riyadh
Google Click ID,Conversion Name,Conversion Time,Conversion Value,Conversion Currency
EAIaIQobChMI...,Qualified Lead,2026-10-05 14:22:10,,SAR
```

### د. ارفعه على Google Ads
1. `Goals` ← `Conversions` ← **Uploads** ← **+**.
2. **Source:** Upload a file ← اختار الملف.
3. **Preview** للتأكد ← **Apply**.
4. بعد كم ساعة بيطلع بـ `Uploads` إذا انقبل أو في أخطاء.

> **ليش يومياً؟** المزايدة الذكية بتتعلم من البيانات الجديدة. إذا رفعت كل أسبوعين، Google بيضل يزايد عمياني طول هالفترة. وفي حد أقصى: النقرة لازم تكون من أقل من **90 يوم**.

---

## 7. القيمة (Conversion Value): تحطها ولا لأ؟

- **بالبداية: بدون قيمة.** كل عميل جاد = تحويل، وخلص.
- **لاحقاً:** إذا في عملاء أهم من غيرهن (مثلاً قرض أكبر = عمولة أكبر)، استعمل `--value`:
  ```powershell
  php artisan landing:export-conversions AB12CD --value=800
  php artisan landing:export-conversions XY34ZW --value=300
  ```
  وبعدين ممكن تنتقل لـ **Maximize conversion value / Target ROAS**. هي مرحلة متقدمة، ما تبلّش فيها.

---

## 8. استراتيجية المزايدة: من الصفر لـ Target CPA

### المشكلة الحقيقية: الكمية
المزايدة الذكية بدها بيانات. Qualified Lead أقل بكتير من نقرات واتساب، فممكن بالبداية Google ما يلاقي معلومات كافية يتعلم منها. لهيك الخطة على مراحل:

### المرحلة 0: الإطلاق (أول 2–3 أسابيع)
| الإعداد | القيمة |
|---|---|
| Bidding | **Maximize conversions** (بدون Target CPA) |
| Primary | **WhatsApp Click + Qualified Lead** سوا |
| الرفع | ابلّش ترفع Qualified Lead من أول يوم |

**ليش؟** نقرة واتساب بتعطي Google بيانات بسرعة يبلّش يتعلم منها، وبنفس الوقت بتكون عم تجمّع تاريخ للعملاء الجادين.

### المرحلة 1: التحويل للعميل الحقيقي
**متى؟** لما يصير عندك تقريباً **15 Qualified Lead أو أكتر بآخر 30 يوم**.
- حوّل `WhatsApp Click` و `Phone Click` لـ **Secondary**.
- خلّي **Qualified Lead** هو الـ Primary الوحيد.
- خلّيك على **Maximize conversions**.
- رح ينزل عدد «التحويلات» بالأرقام، وهاد طبيعي لأنك صرت عم تعدّ الجادين بس.

### المرحلة 2: Target CPA
**متى؟** لما يصير عندك **30 Qualified Lead أو أكتر بآخر 30 يوم**، والأداء مستقر أسبوعين.
1. احسب الـ CPA الفعلي: `التكلفة بآخر 30 يوم ÷ عدد Qualified Leads`.
   مثال: 6,000 ريال ÷ 30 = **200 ريال** لكل عميل جاد.
2. `Campaign` ← `Settings` ← `Bidding` ← Maximize conversions ← ✅ **Set a target cost per action**.
3. **حط الهدف = الـ CPA الفعلي أو أعلى منه بـ 10%.** يعني 200–220 ريال.
   ⚠️ إذا حطيته أقل بكتير (مثلاً 100)، Google بيوقف يعرض الإعلان تقريباً.
4. بعدها نزّله بالتدريج: **15% بحد أقصى كل مرة، وكل أسبوعين**.

### قواعد مهمة طول الوقت
- **فترة التعلم (Learning):** بعد أي تغيير كبير بيطلع `Learning` جنب الحملة، وبيطوّل بين أسبوع وأسبوعين. **ما تغيّر شي خلالها.**
- **التغييرات الكبيرة** بترجّع فترة التعلم: تغيير الاستراتيجية، تغيير Target CPA أكتر من 20%، تغيير الميزانية أكتر من 20%، تغيير الـ Primary conversion.
- **ما تحكم على يوم أو يومين.** احكم على أسبوع لأسبوعين.
- **التأخير طبيعي:** التحويلات المرفوعة بتتسجل على **تاريخ النقرة**، مش تاريخ الرفع. فأرقام الأيام الأخيرة دايماً بتكون أقل من الحقيقة لحتى ترفع.

---

## 9. المراقبة الأسبوعية

| شو تشوف | وين | شو بيعني |
|---|---|---|
| Conversions و Cost / conv. | الحملة | الكلفة الفعلية لكل عميل جاد |
| All conv. | الحملة | كل نقرات واتساب والاتصال |
| نسبة التأهيل = Conversions ÷ All conv. | حساب يدوي | إذا نزلت، يعني الترافيك عم يسوء |
| Search terms | Insights and reports | ضيف الكلمات السيئة كـ Negative |
| Invalid clicks | ضيفه من Columns | النقرات اللي Google لغاها |
| Uploads | Goals ← Conversions | تأكد ما في أخطاء رفع |

---

## 10. أخطاء الرفع الشائعة

| الخطأ | السبب | الحل |
|---|---|---|
| Conversion action not found | الاسم مش مطابق، أو التحويل جديد كتير | طابق الاسم حرفياً مع `.env`، واستنّى يوم |
| Click not found / Invalid GCLID | الـ gclid من حساب تاني، أو الـ Auto-tagging كان مطفّى | تأكد من Auto-tagging والحساب |
| Too old click | النقرة أقدم من الـ click-through window | ارفع أسرع (يومياً) |
| Conversion precedes click | وقت التحويل قبل وقت النقرة | ما لازم يصير مع الأمر لأنه بيستعمل وقت التأهيل، بس تأكد إنو ساعة السيرفر مظبوطة |
| Duplicate | رفعت نفس الـ gclid مرتين | طبيعي، Google بيتجاهل التكرار |

---

## 11. قاموس سريع

| المصطلح | المعنى |
|---|---|
| **gclid** | معرّف فريد لكل نقرة على إعلان Google |
| **Primary action** | تحويل بيستعمله Google للمزايدة وبيطلع بعمود Conversions |
| **Secondary action** | تحويل للمراقبة بس، بيطلع بـ All conv. |
| **Maximize conversions** | Google بيجيب أكبر عدد تحويلات ضمن الميزانية |
| **Target CPA** | Google بيحاول يجيب تحويلات بمعدل كلفة محدد |
| **CPA** | Cost Per Acquisition: الكلفة لكل تحويل |
| **Learning period** | فترة بعد التغيير عم يتعلم فيها النظام، والأداء بيكون متذبذب |
| **Offline conversion import** | رفع تحويلات صارت برّا الموقع (واتساب، تلفون) وربطها بالنقرة |

---

## الخلاصة بسطر
**ارفع العملاء الجادين يومياً ← ابلّش بـ Maximize conversions ← خلّي Qualified Lead هو الهدف الوحيد لما يوصل لـ 15 بالشهر ← فعّل Target CPA لما يوصل لـ 30 ← نزّل الهدف بالتدريج.**
