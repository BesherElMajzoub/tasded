@extends('layouts.app')

@section('title', 'سياسة الخصوصية | أصل القمة')
@section('description', 'سياسة خصوصية موقع أصل القمة.')
@section('canonical', route('legal.privacy'))

@section('body')
<header class="legal-header">
    <div class="container header-inner">
        <a href="{{ route('landing.sadad') }}" aria-label="العودة إلى الرئيسية"><x-brand-logo /></a>
        <a href="{{ route('landing.sadad') }}" class="text-link">العودة للرئيسية</a>
    </div>
</header>
<main id="main-content" class="legal-page">
    <article class="container legal-card">
        <p class="eyebrow">وثيقة قيد الاعتماد</p>
        <h1>سياسة الخصوصية</h1>
        <div class="notice notice--warning"><x-icon name="info" /><p>هذه مسودة تشغيلية وليست بديلًا عن المراجعة القانونية. يجب اعتمادها قبل النشر.</p></div>
        <h2>البيانات التي نتعامل معها</h2>
        <p>لا يتضمن الموقع نموذجًا لجمع البيانات المالية. عند التواصل عبر الهاتف أو واتساب، تخضع البيانات لسياسات مزود الخدمة ولإجراءات الجهة المشغلة.</p>
        <h2>بيانات القياس</h2>
        <p>قد تُستخدم أدوات قياس بعد إعداد معرفاتها واعتماد آلية الموافقة. تقيس النقر على روابط الاتصال وواتساب دون إرسال أسماء أو هويات أو تفاصيل ديون.</p>
        <h2>الغرض والاحتفاظ</h2>
        <p>تُستخدم البيانات للرد على الاستفسار، وتقييم ملاءمة الخدمة، وتحسين التجربة. يجب أن تعتمد الجهة مدد الاحتفاظ وإجراءات طلبات أصحاب البيانات قبل النشر.</p>
        <h2>التواصل</h2>
        <p>للاستفسار عن الخصوصية، تواصل عبر الرقم <a href="tel:{{ $landing['business']['phone'] }}">{{ $landing['business']['phone_display'] }}</a>.</p>
    </article>
</main>
@endsection
