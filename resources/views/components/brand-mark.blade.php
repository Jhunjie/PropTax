@props(['size' => 'md'])
{{--
    Placeholder logo lives at public/images/logo.png — swap that file for the
    real provincial seal whenever it's ready, no code changes needed.
--}}
<div {{ $attributes->merge(['class' => 'brand-mark brand-mark-'.$size]) }}>
    <img src="{{ asset('images/logo.png') }}" alt="{{ __('Province of Negros Occidental seal') }}" class="brand-mark-logo">
    <div class="brand-mark-text">
        <span class="brand-mark-title">{{ __('PROVINCE OF NEGROS OCCIDENTAL') }}</span>
        <span class="brand-mark-sub">{{ __('Online Real Property Tax Payment') }}</span>
    </div>
</div>
