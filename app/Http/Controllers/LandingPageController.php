<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class LandingPageController extends Controller
{
    public function __invoke(?string $variant = null): View
    {
        $landing = config('landing');
        $variantConfig = $variant ? $landing['variants'][$variant] : [];

        $landing['hero'] = array_merge($landing['hero'], $variantConfig['hero'] ?? []);

        $meta = [
            'title' => $variantConfig['meta_title'] ?? 'سداد القروض في الرياض وجدة | أصل القمة',
            'description' => $variantConfig['meta_description'] ?? 'خدمات استشارية وتنسيقية لسداد القروض القائمة وطلب تمويل جديد في الرياض وجدة، مع توضيح النطاق والاستثناءات.',
            'canonical' => $variant ? route('landing.variant', $variant) : route('landing.sadad'),
        ];

        $whatsappUrl = sprintf(
            'https://wa.me/%s?text=%s',
            $landing['business']['whatsapp'],
            rawurlencode($landing['whatsapp_message']),
        );

        $structuredData = $this->structuredData($landing, $meta);

        return view('landing', compact('landing', 'whatsappUrl', 'meta', 'variant', 'structuredData'));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function structuredData(array $landing, array $meta): array
    {
        $business = $landing['business'];

        return [
            array_filter([
                '@context' => 'https://schema.org',
                '@type' => 'FinancialService',
                'name' => $business['name'],
                'legalName' => $business['legal_name'],
                'url' => $meta['canonical'],
                'telephone' => $business['phone'],
                'image' => asset('images/riyadh-hero.webp'),
                'description' => $meta['description'],
                'address' => $business['address'],
                'areaServed' => array_map(
                    fn (string $city) => ['@type' => 'City', 'name' => $city],
                    $business['cities'],
                ),
            ]),
            [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn (array $item) => [
                    '@type' => 'Question',
                    'name' => $item['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
                ], $landing['faq']),
            ],
        ];
    }
}
