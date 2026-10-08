@props(['placement', 'class' => ''])

@php
    $enabled = $adsEnabled ?? false;
    $adCode = ($enabled && isset($websiteAds[$placement]) && !empty(trim($websiteAds[$placement]))) 
        ? $websiteAds[$placement] 
        : null;
@endphp

@if($adCode)
    <div class="w-full block text-center clear-both ad-slot-wrapper {{ $class }}" data-ad-placement="{{ $placement }}">
        <div class="w-full max-w-full mx-auto text-center">
            <span class="ad-label text-[10px] tracking-widest uppercase font-black text-slate-400 dark:text-slate-500 mb-1.5 select-none text-center hidden">
                Creator Sponsor
            </span>
            <div class="w-full max-w-full block text-center min-w-0 overflow-x-auto overflow-y-hidden [&_.adsbygoogle]:!block [&_.adsbygoogle]:!w-full [&_.adsbygoogle]:!min-w-[250px] [&_.adsbygoogle]:!mx-auto">
                {!! $adCode !!}
            </div>
        </div>
    </div>
@endif
