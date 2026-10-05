@extends('layouts.app')

@section('title', 'الشروط والأحكام | أصل القمة')
@section('description', 'شروط استخدام موقع أصل القمة ونطاق الخدمة.')
@section('canonical', route('legal.terms'))

@section('body')
<header class="legal-header"><div class="container header-inner"><a href="{{ route('landing.sadad') }}"><x-brand-logo /></a><a href="{{ route('landing.sadad') }}" class="text-link">العودة للرئيسية</a></div></header>
<main id="main-content" class="legal-page">
    <article class="container legal-card">
        <p class="eyebrow">وثيقة قيد الاعتماد</p>
        <h1>الشروط والأحكام</h1>
        <div class="notice notice--warning"><x-icon name="info" /><p>يجب مراجعة هذه المسودة واعتماد بيانات الجهة والرسوم والالتزامات قبل النشر.</p></div>
        <h2>طبيعة الخدمة</h2>
        <p>أصل القمة يقدم خدمات استشارية وتنسيقية، وليس بنكًا أو جهة تمويل، ولا يقدم قروضًا مباشرة.</p>
        <h2>نطاق الخدمة</h2>
        <p>النطاق المعلن هو الرياض وجدة، ولا يشمل تسديد قروض القطاع الخاص أو الالتزامات السكنية. تخضع ملاءمة الحالة للمراجعة.</p>
        <h2>الموافقة والرسوم</h2>
        <p>لا توجد موافقة تمويلية مضمونة؛ فالقرار يعود إلى الجهة الممولة وشروطها. تفاصيل رسوم الخدمة قيد الاعتماد ويجب الاطلاع عليها والموافقة عليها قبل بدء الإجراءات.</p>
        <h2>الاستخدام المسؤول</h2>
        <p>لا ترسل رقم الهوية أو المستندات المصرفية في رسالة الاستفسار الأولى. يجب تقديم معلومات صحيحة وعدم إساءة استخدام قنوات التواصل.</p>
    </article>
</main>
@endsection
