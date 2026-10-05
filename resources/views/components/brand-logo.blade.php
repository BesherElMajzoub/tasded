@props(['compact' => false])

<span {{ $attributes->merge(['class' => 'brand-logo']) }}>
    <svg aria-hidden="true" viewBox="0 0 46 46" class="brand-logo__mark">
        <path d="M23 3 42 13.5v19L23 43 4 32.5v-19L23 3Z" fill="currentColor" opacity=".12"/>
        <path d="M23 7.5 37.8 16v9.3L23 33.8 8.2 25.3V16L23 7.5Z" fill="none" stroke="currentColor" stroke-width="2.5"/>
        <path d="m14 22.6 9 5.2 9-5.2M23 27.8v10.1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/>
    </svg>
    @unless ($compact)
        <span>
            <strong>أصل القمة</strong>
            <small>للاستشارات والتنسيق</small>
        </span>
    @endunless
</span>
