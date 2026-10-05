@props(['eyebrow' => null, 'title', 'description' => null, 'light' => false])

<header {{ $attributes->merge(['class' => 'section-heading'.($light ? ' section-heading--light' : '')]) }}>
    @if ($eyebrow)
        <p class="eyebrow">{{ $eyebrow }}</p>
    @endif
    <h2>{{ $title }}</h2>
    @if ($description)
        <p>{{ $description }}</p>
    @endif
</header>
