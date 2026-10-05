@props(['landing', 'whatsappUrl', 'compact' => false, 'inverse' => false])

<div {{ $attributes->merge(['class' => $compact ? 'contact-actions contact-actions--compact' : 'contact-actions']) }}>
    <a
        href="{{ $whatsappUrl }}"
        class="button {{ $inverse ? 'button--light' : 'button--primary' }}"
        target="_blank"
        rel="noopener noreferrer"
        data-track="whatsapp_click"
        aria-label="استفسر عبر واتساب"
    >
        <x-icon name="whatsapp" />
        <span>استفسر عبر واتساب</span>
    </a>
    <a
        href="tel:{{ $landing['business']['phone'] }}"
        class="button {{ $inverse ? 'button--outline-light' : 'button--secondary' }}"
        data-track="phone_click"
        aria-label="اتصل الآن على {{ $landing['business']['phone_display'] }}"
    >
        <x-icon name="phone" />
        <span>{{ $compact ? 'اتصل الآن' : 'اتصل الآن — '.$landing['business']['phone_display'] }}</span>
    </a>
</div>
