@props(['placement', 'class' => ''])

@php
    $enabled = $adsEnabled ?? false;
    $rawAdCode = ($enabled && isset($websiteAds[$placement]) && !empty(trim($websiteAds[$placement]))) 
        ? $websiteAds[$placement] 
        : null;

    // Strip duplicate adsbygoogle.js library calls so the script is never downloaded multiple times on one page
    $adCode = $rawAdCode ? preg_replace('#<script[^>]*src=["\'][^"\']*adsbygoogle\.js[^"\']*["\'][^>]*>\s*<\/script>#i', '', $rawAdCode) : null;
@endphp

@if($adCode)
    <div class="w-full block text-center clear-both ad-slot-wrapper {{ $class }}" 
         data-ad-placement="{{ $placement }}"
         style="display:block;width:100%;height:0;min-height:0;max-height:0;margin:0;padding:0;overflow:hidden;opacity:0;border:none;clear:both;">
        <div class="w-full max-w-full mx-auto text-center ad-slot-inner" style="height:0;max-height:0;overflow:hidden;margin:0;padding:0;">
            <span class="ad-label text-[10px] tracking-widest uppercase font-black text-slate-400 dark:text-slate-500 mb-1.5 select-none text-center hidden">
                Creator Sponsor
            </span>
            <div class="w-full max-w-full block text-center min-w-0 overflow-x-auto overflow-y-hidden [&_.adsbygoogle]:!block [&_.adsbygoogle]:!w-full [&_.adsbygoogle]:!min-w-[250px] [&_.adsbygoogle]:!mx-auto">
                {!! $adCode !!}
            </div>
        </div>
    </div>
@endif
