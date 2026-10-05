<?php

use App\Models\ContactClick;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('serves ad-group variants with matching headline and self canonical', function () {
    $response = $this->get(route('landing.variant', 'jeddah'));

    $response
        ->assertOk()
        ->assertSee('<title>سداد القروض في جدة | أصل القمة</title>', escape: false)
        ->assertSee('حلول لسداد قروضك القائمة وطلب تمويل جديد في جدة')
        ->assertSee('<link rel="canonical" href="'.route('landing.variant', 'jeddah').'">', escape: false)
        ->assertSee('data-variant="jeddah"', escape: false);
});

it('returns 404 for unknown variants', function () {
    $this->get('/sadad-alqorood/unknown')->assertNotFound();
});

it('outputs structured data for the business and faq', function () {
    $this->get(route('landing.sadad'))
        ->assertSee('application/ld+json', escape: false)
        ->assertSee('"@type":"FinancialService"', escape: false)
        ->assertSee('"@type":"FAQPage"', escape: false);
});

it('stores contact clicks with ad attribution', function () {
    $this->post(route('contact-clicks.store'), [
        'ref' => 'AB12CD',
        'channel' => 'whatsapp',
        'variant' => 'riyadh',
        'page_path' => '/sadad-alqorood/riyadh',
        'gclid' => 'test-gclid',
        'utm_campaign' => 'sadad',
    ])->assertNoContent();

    expect(ContactClick::firstWhere('ref', 'AB12CD'))
        ->channel->toBe('whatsapp')
        ->gclid->toBe('test-gclid')
        ->utm_campaign->toBe('sadad');
});

it('rejects malformed contact clicks', function () {
    $this->post(route('contact-clicks.store'), ['ref' => 'bad', 'channel' => 'email'])
        ->assertStatus(422);

    expect(ContactClick::count())->toBe(0);
});

it('exports qualified leads as a google ads offline conversion csv', function () {
    Storage::fake('local');
    ContactClick::create(['ref' => 'AB12CD', 'channel' => 'whatsapp', 'gclid' => 'test-gclid']);
    ContactClick::create(['ref' => 'ZZ99ZZ', 'channel' => 'phone']);

    $this->artisan('landing:export-conversions', ['refs' => ['ab12cd', 'ZZ99ZZ'], '--value' => '500'])
        ->assertSuccessful();

    $csv = Storage::disk('local')->get(Storage::disk('local')->files('conversions')[0]);

    expect($csv)
        ->toContain('Parameters:TimeZone=Asia/Riyadh')
        ->toContain('test-gclid,Qualified Lead,')
        ->toContain(',500,SAR')
        ->not->toContain('ZZ99ZZ')
        ->and(ContactClick::whereNotNull('qualified_at')->count())->toBe(2);
});
