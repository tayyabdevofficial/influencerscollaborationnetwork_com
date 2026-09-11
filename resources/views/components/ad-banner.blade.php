@props(['placement', 'class' => ''])

@php
    $enabled = $adsEnabled ?? false;
    $adCode = ($enabled && isset($websiteAds[$placement]) && !empty(trim($websiteAds[$placement]))) 
        ? $websiteAds[$placement] 
        : null;
@endphp

@if($adCode)
    <div class="w-full my-6 flex flex-col items-center justify-center overflow-hidden {{ $class }}" data-ad-placement="{{ $placement }}">
        <div class="w-full max-w-full flex flex-col items-center">
            <span class="text-[10px] tracking-widest uppercase font-black text-slate-400 dark:text-slate-500 mb-1.5 select-none text-center block">
                Creator Sponsor
            </span>
            <div class="w-full flex justify-center items-center overflow-x-auto overflow-y-hidden text-center max-w-full">
                {!! $adCode !!}
            </div>
        </div>
    </div>
@endif
