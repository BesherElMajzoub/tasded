<?php

it('renders the landing page with transparent service information', function () {
    $response = $this->get(route('landing.sadad'));

    $response
        ->assertOk()
        ->assertSee('حلول لسداد قروضك القائمة وطلب تمويل جديد')
        ->assertSee('لا نوفر القروض مباشرة')
        ->assertSee('القطاع الخاص')
        ->assertSee('الالتزامات السكنية')
        ->assertSee('نسبة المرابحة السنوية')
        ->assertSee('تبدأ من 2%')
        ->assertSee('من 12 إلى 60 شهرًا')
        ->assertSee('قرض بمبلغ 50,000 ريال')
        ->assertSee('إجمالي المبلغ المستحق للسداد 51,000 ريال')
        ->assertSee('tel:+966554192032', escape: false)
        ->assertSee('https://wa.me/966554192032', escape: false);
});

it('does not publish removed debt wording', function () {
    $response = $this->get(route('landing.sadad'));

    $response
        ->assertDontSee('المديونية')
        ->assertDontSee('متعثرات')
        ->assertDontSee('متعثر');
});

it('supports campaign attribution parameters without changing the response', function () {
    $response = $this->get(route('landing.sadad', [
        'utm_source' => 'google',
        'utm_campaign' => 'sadad',
        'gclid' => 'test-click-id',
    ]));

    $response
        ->assertOk()
        ->assertSee('أصل القمة');
});

it('renders legal pages and marks them as pending approval', function (string $routeName) {
    $response = $this->get(route($routeName));

    $response
        ->assertOk()
        ->assertSee('قيد الاعتماد');
})->with([
    'privacy policy' => 'legal.privacy',
    'terms and conditions' => 'legal.terms',
]);

it('adds baseline security headers', function () {
    $response = $this->get(route('landing.sadad'));

    $response
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

it('keeps the document hierarchy and external contact links accessible', function () {
    $response = $this->get(route('landing.sadad'));
    $html = $response->getContent();

    expect(substr_count($html, '<h1'))->toBe(1)
        ->and($html)->toContain('lang="ar" dir="rtl"')
        ->and($html)->toContain('rel="noopener noreferrer"')
        ->and($html)->toContain('href="#main-content"');
});

it('renders tracked floating contact actions', function () {
    $response = $this->get(route('landing.sadad'));

    $response
        ->assertOk()
        ->assertSee('class="floating-actions"', escape: false)
        ->assertSee('class="floating-action floating-action--whatsapp"', escape: false)
        ->assertSee('class="floating-action floating-action--phone"', escape: false)
        ->assertSee('data-track="whatsapp_click"', escape: false)
        ->assertSee('data-track="phone_click"', escape: false);
});
