@extends('layouts.app')

@section('title', $meta['title'])
@section('description', $meta['description'])
@section('canonical', $meta['canonical'])

@push('head')
    <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('body')
<header class="site-header" data-header>
    <div class="container header-inner">
        <a href="#home" class="logo-link" aria-label="{{ $landing['business']['name'] }} - الرئيسية"><x-brand-logo /></a>
        <nav class="desktop-nav" aria-label="التنقل الرئيسي">
            <a href="#home">الرئيسية</a>
            <a href="#services">الخدمات</a>
            <a href="#process">كيف نعمل</a>
            <a href="#faq">الأسئلة الشائعة</a>
            <a href="#contact">تواصل معنا</a>
        </nav>
        <div class="header-actions">
            <a href="tel:{{ $landing['business']['phone'] }}" class="header-phone" data-track="phone_click" aria-label="الاتصال على {{ $landing['business']['phone_display'] }}">
                <x-icon name="phone" /><span dir="ltr">{{ $landing['business']['phone_display'] }}</span>
            </a>
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button--primary header-cta" data-track="whatsapp_click">استفسر الآن</a>
            <button class="menu-toggle" type="button" aria-label="فتح قائمة التنقل" aria-expanded="false" aria-controls="mobile-menu" data-menu-toggle><x-icon name="menu" /></button>
        </div>
    </div>
    <nav id="mobile-menu" class="mobile-nav" aria-label="التنقل للهاتف" hidden data-mobile-menu>
        <a href="#services">الخدمات</a><a href="#process">كيف نعمل</a><a href="#faq">الأسئلة الشائعة</a><a href="#contact">تواصل معنا</a>
    </nav>
</header>

<main id="main-content">
    <section id="home" class="hero" aria-labelledby="hero-title">
        <div class="hero-glow" aria-hidden="true"></div>
        <div class="container hero-grid">
            <div class="hero-content">
                <div class="hero-eyebrow"><span></span>{{ $landing['hero']['eyebrow'] }}</div>
                <h1 id="hero-title">{{ $landing['hero']['title'] }}</h1>
                <p class="hero-description">{{ $landing['hero']['description'] }}</p>
                <ul class="hero-facts" aria-label="معلومات سريعة">
                    <li><x-icon name="check" />خدمات استشارية وتنسيقية</li>
                    <li><x-icon name="location" />الرياض وجدة</li>
                    <li><x-icon name="info" />لا تشمل تسديد قروض القطاع الخاص أو الالتزامات السكنية</li>
                </ul>
                <x-contact-actions :landing="$landing" :whatsapp-url="$whatsappUrl" />
                <p class="hero-disclaimer"><x-icon name="shield" />نقدم خدمات التنسيق والاستشارة، ولا نوفر القروض مباشرة.</p>
            </div>
            <div class="hero-visual" aria-label="مشهد عمراني لمدينة الرياض">
                <div class="hero-image-wrap">
                    {{-- The visual is hidden below 800px; the 1px source stops phones downloading the photo. --}}
                    <picture>
                        <source media="(max-width: 800px)" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
                        <img src="{{ asset('images/riyadh-hero.webp') }}" width="1536" height="1024" fetchpriority="high" alt="إطلالة عمرانية حديثة على مدينة الرياض">
                    </picture>
                    <div class="image-overlay" aria-hidden="true"></div>
                </div>
                <div class="visual-card visual-card--top"><x-icon name="shield" /><span><small>خدمة واضحة</small><strong>بلا وعود مضمونة</strong></span></div>
                <div class="visual-card visual-card--bottom"><x-icon name="location" /><span><small>نطاق الخدمة</small><strong>الرياض وجدة</strong></span></div>
            </div>
        </div>
    </section>

    <section class="trust-strip" aria-label="مبادئ الخدمة">
        <div class="container trust-strip__grid">
            <div><x-icon name="shield" /><span><strong>شفافية قبل البدء</strong><small>توضيح النطاق والشروط</small></span></div>
            <div><x-icon name="document" /><span><strong>خطوات مفهومة</strong><small>مراجعة الحالة أولًا</small></span></div>
            <div><x-icon name="phone" /><span><strong>تواصل مباشر</strong><small>هاتف أو واتساب</small></span></div>
        </div>
    </section>

    <section id="eligibility" class="section section--mint">
        <div class="container">
            <x-section-heading eyebrow="نطاق واضح" title="هل الخدمة مناسبة لك؟" description="تعرّف على نطاق الخدمات والحالات المشمولة وغير المشمولة قبل التواصل." />
            <div class="eligibility-grid">
                <article class="info-card"><span class="card-icon"><x-icon name="location" /></span><h3>نطاق الخدمة</h3><ul>@foreach ($landing['business']['cities'] as $city)<li><x-icon name="check" />{{ $city }}</li>@endforeach</ul></article>
                <article class="info-card"><span class="card-icon"><x-icon name="balance" /></span><h3>الخدمات المعلنة</h3><ul><li><x-icon name="check" />سداد القروض القائمة</li><li><x-icon name="check" />طلب تمويل جديد</li><li><x-icon name="check" />التنسيق والاستشارة</li></ul></article>
                <article class="info-card"><span class="card-icon"><x-icon name="document" /></span><h3>الفئات المستهدفة</h3><ul>@foreach ($landing['eligible_audiences'] as $audience)<li><x-icon name="check" />{{ $audience }}</li>@endforeach</ul></article>
                <article class="info-card info-card--muted"><span class="card-icon"><x-icon name="info" /></span><h3>حالات غير مشمولة</h3><p>نوضحها مسبقًا لتوفير وقتك:</p><ul>@foreach ($landing['exclusions'] as $exclusion)<li><span class="minus">—</span>{{ $exclusion }}</li>@endforeach</ul></article>
            </div>
        </div>
    </section>

    <section id="services" class="section">
        <div class="container">
            <x-section-heading eyebrow="ماذا نقدم؟" title="خدماتنا" description="حلول استشارية وتنسيقية للمعاملات المالية المتعلقة بالقروض والتمويل." />
            <div class="services-grid">
                @foreach ($landing['services'] as $service)
                    <article class="service-card">
                        <div class="service-card__number">0{{ $loop->iteration }}</div><span class="service-icon"><x-icon :name="$service['icon']" /></span>
                        <h3>{{ $service['title'] }}</h3><p>{{ $service['description'] }}</p>
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" data-track="whatsapp_click" class="text-link">استفسر عن الخدمة <span aria-hidden="true">←</span></a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="financing-details" class="section financing-section" aria-label="تفاصيل النسبة وفترة السداد والمثال التوضيحي">
        <div class="container">
            <x-section-heading eyebrow="التفاصيل المالية" title="النسبة والفترة والمثال التوضيحي" description="معلومات أساسية تساعدك على فهم التصور المبدئي قبل التواصل، وتبقى الشروط النهائية خاضعة لتقييم الجهة الممولة." />
            <div class="financing-grid">
                @foreach ($landing['financing_details'] as $detail)
                    <article class="financing-card {{ $loop->last ? 'financing-card--example' : '' }}">
                        <span class="financing-icon"><x-icon :name="$detail['icon']" /></span>
                        <div>
                            <h3>{{ $detail['title'] }}</h3>
                            <strong>{{ $detail['value'] }}</strong>
                            <p>{{ $detail['description'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="financing-note">
                <x-icon name="info" />
                <p><strong>التنويه:</strong> المثال توضيحي وغير ملزم، ولا يمثل عرضًا تمويليًا نهائيًا. تتحدد النسبة وفترة السداد والتكلفة الفعلية وفق تقييم البنك والملف الائتماني للعميل وشروط الجهة الممولة.</p>
            </div>
        </div>
    </section>

    <section id="process" class="section section--dark">
        <div class="container">
            <x-section-heading light eyebrow="رحلة واضحة" title="كيف نعمل؟" description="خطوات واضحة للتعرّف على الخدمة قبل بدء الإجراءات." />
            <ol class="timeline">
                @foreach ($landing['workflow'] as $step)
                    <li><div class="step-number">{{ sprintf('%02d', $loop->iteration) }}</div><div><h3>{{ $step['title'] }}</h3><p>{{ $step['description'] }}</p></div></li>
                @endforeach
            </ol>
            <p class="process-note"><x-icon name="info" />هذا التسلسل مقترح ويحتاج إلى اعتماد صاحب النشاط قبل النشر.</p>
        </div>
    </section>

    <section id="transparency" class="section">
        <div class="container transparency-grid">
            <div><x-section-heading eyebrow="الثقة تبدأ بالمعلومة" title="معلومات مهمة قبل طلب الخدمة" description="نحرص على أن تعرف طبيعة الخدمة وما يلزم اعتماده قبل أي إجراء." /></div>
            <div class="transparency-content">
                <article class="disclosure-card disclosure-card--primary"><span><x-icon name="shield" /></span><div><h3>طبيعة النشاط</h3><p>{{ $landing['disclosures'][0] }}</p></div></article>
                <article class="disclosure-card disclosure-card--warning"><span><x-icon name="info" /></span><div><h3>الرسوم والتكاليف</h3><p>{{ $landing['fees']['summary'] }}</p></div></article>
                <div class="business-facts">
                    <div><small>الاسم التجاري</small><strong>{{ $landing['business']['name'] }}</strong></div>
                    <div><small>الهاتف الرسمي</small><a href="tel:{{ $landing['business']['phone'] }}" dir="ltr">{{ $landing['business']['phone_display'] }}</a></div>
                    <div class="pending-fact"><small>الاسم القانوني والسجل</small><strong>{{ $landing['business']['legal_name'] ?: 'يلزم الاعتماد قبل النشر' }}</strong></div>
                    <div class="pending-fact"><small>العنوان الفعلي</small><strong>{{ $landing['business']['address'] ?: 'يلزم الاعتماد قبل النشر' }}</strong></div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="section section--mint">
        <div class="container faq-grid">
            <div class="faq-intro"><x-section-heading eyebrow="إجابات مباشرة" title="الأسئلة الشائعة" description="إجابات عن أهم الأسئلة المتعلقة بنطاق خدماتنا." /><div class="faq-help"><x-icon name="phone" /><div><small>لم تجد إجابتك؟</small><a href="tel:{{ $landing['business']['phone'] }}" data-track="phone_click">تواصل معنا</a></div></div></div>
            <div class="faq-list">
                @foreach ($landing['faq'] as $item)
                    <details data-faq>
                        <summary><span>{{ $item['question'] }}</span><span class="faq-plus" aria-hidden="true"></span></summary>
                        <div class="faq-answer"><p>{{ $item['answer'] }}</p></div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <section id="contact" class="final-cta">
        <div class="container final-cta__inner">
            <div><p class="eyebrow">الخطوة الأولى</p><h2>هل تريد معرفة الخيارات المتاحة لحالتك؟</h2><p>تواصل مع أصل القمة للاستفسار عن نطاق الخدمات وشروطها والخطوات المتعلقة بطلبك.</p></div>
            <div class="final-cta__actions"><x-contact-actions :landing="$landing" :whatsapp-url="$whatsappUrl" inverse /><a href="tel:{{ $landing['business']['phone'] }}" class="display-phone" dir="ltr">{{ $landing['business']['phone_display'] }}</a></div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand"><x-brand-logo /><p>خدمات استشارية وتنسيقية للمعاملات المالية في الرياض وجدة. لا نقدم القروض مباشرة.</p></div>
        <div><h2>روابط سريعة</h2><ul><li><a href="#services">الخدمات</a></li><li><a href="#process">كيف نعمل</a></li><li><a href="#transparency">الشفافية</a></li><li><a href="#faq">الأسئلة الشائعة</a></li></ul></div>
        <div><h2>بيانات التواصل</h2><ul><li><a href="tel:{{ $landing['business']['phone'] }}" dir="ltr">{{ $landing['business']['phone_display'] }}</a></li><li>الرياض وجدة</li><li class="footer-pending">{{ $landing['business']['address'] ?: 'العنوان الفعلي: قيد الاعتماد' }}</li></ul></div>
        <div><h2>معلومات نظامية</h2><ul><li><a href="{{ route('legal.privacy') }}">سياسة الخصوصية</a></li><li><a href="{{ route('legal.terms') }}">الشروط والأحكام</a></li><li class="footer-pending">بيانات الجهة القانونية: قيد الاعتماد</li></ul></div>
    </div>
    <div class="container footer-bottom"><p>© {{ date('Y') }} {{ $landing['business']['name'] }}. جميع الحقوق محفوظة.</p><p>معلومات هذه النسخة معدة للمراجعة قبل الإطلاق.</p></div>
</footer>

<div class="floating-actions" aria-label="أزرار التواصل العائمة">
    <a
        href="{{ $whatsappUrl }}"
        class="floating-action floating-action--whatsapp"
        target="_blank"
        rel="noopener noreferrer"
        data-track="whatsapp_click"
        aria-label="التواصل عبر واتساب"
    >
        <x-icon name="whatsapp" />
        <span>واتساب</span>
    </a>
    <a
        href="tel:{{ $landing['business']['phone'] }}"
        class="floating-action floating-action--phone"
        data-track="phone_click"
        aria-label="الاتصال على {{ $landing['business']['phone_display'] }}"
    >
        <x-icon name="phone" />
        <span>اتصال</span>
    </a>
</div>

<div class="mobile-contact-bar" aria-label="خيارات التواصل السريع">
    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" data-track="whatsapp_click"><x-icon name="whatsapp" /><span>واتساب</span></a>
    <a href="tel:{{ $landing['business']['phone'] }}" data-track="phone_click"><x-icon name="phone" /><span>اتصال</span></a>
</div>
@endsection
