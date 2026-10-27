@extends('catalog::layouts.catalog')

@section('title', ($article['data']['article_nr'] ?? 'Part Detail'))

@section('content')
@php
    // Article detail: flat object under data
    $data    = $article['data']           ?? [];
    $images  = $media['data']['results']  ?? $media['data'] ?? [];
    $specs   = $criteria['data']['results'] ?? $criteria['data'] ?? [];
    $oem     = $crosses['data']['results']  ?? $crosses['data'] ?? [];
    $altList = $analogs['data']['results']  ?? $analogs['data'] ?? [];

    // Normalise images to array
    if (!empty($images) && !isset($images[0])) {
        $images = [$images];
    }
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left column: image + specs --}}
    <div class="space-y-4">

        {{-- Main image --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-4 flex items-center justify-center min-h-52">
            @php $firstImage = collect($images)->first(fn($i) => !empty($i['img_url'] ?? $i['url'] ?? null)); @endphp
            @if($firstImage)
                <img src="{{ $firstImage['img_url'] ?? $firstImage['url'] }}"
                     alt="{{ $data['article_nr'] ?? '' }}"
                     class="max-h-60 max-w-full object-contain">
            @else
                <div class="text-gray-300 text-sm">No image available</div>
            @endif
        </div>

        {{-- Technical specifications --}}
        @if(!empty($specs))
            <div class="bg-white border border-gray-100 rounded-2xl p-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Specifications</h3>
                <div class="space-y-2">
                    @foreach($specs as $spec)
                        <div class="flex justify-between text-sm gap-2">
                            <span class="text-gray-400 flex-shrink-0">
                                {{ $spec['description'] ?? $spec['name'] ?? $spec['crt_description'] ?? '' }}
                            </span>
                            <span class="text-gray-800 font-medium text-right">
                                {{ $spec['value'] ?? $spec['crt_value'] ?? '' }}
                                @if(!empty($spec['unit'])) <span class="text-gray-400 text-xs">{{ $spec['unit'] }}</span> @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Right column: article info --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Main info card --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6">
            <div class="text-xs text-gray-400 uppercase tracking-widest mb-1">
                {{ $data['brand'] ?? '' }}
            </div>
            <h1 class="text-2xl font-bold font-mono text-gray-800">
                {{ $data['article_nr'] ?? '—' }}
            </h1>
            @if(!empty($data['part_name'] ?? $data['product_name'] ?? null))
                <p class="text-gray-500 mt-2 text-lg">
                    {{ $data['part_name'] ?? $data['product_name'] }}
                </p>
            @endif
            @if(!empty($data['art_id']))
                <p class="text-xs text-gray-300 font-mono mt-2">art_id: {{ $data['art_id'] }}</p>
            @endif
        </div>

        {{-- OEM / Cross-reference numbers --}}
        @if(!empty($oem))
            <div class="bg-white border border-gray-100 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    OEM / Cross-Reference Numbers
                    <span class="text-xs font-normal text-gray-400 ml-1">({{ count($oem) }})</span>
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($oem as $cross)
                        <span class="bg-gray-50 border border-gray-200 text-xs font-mono px-2 py-1 rounded">
                            {{ $cross['oe_number'] ?? $cross['cross_nr'] ?? $cross['article_nr'] ?? $cross['number'] ?? '?' }}
                            @if(!empty($cross['mfa_name'] ?? $cross['brand'] ?? null))
                                <span class="text-gray-400 ml-1">{{ $cross['mfa_name'] ?? $cross['brand'] }}</span>
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Analog parts --}}
        @if(!empty($altList))
            <div class="bg-white border border-gray-100 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Analog Parts
                    <span class="text-xs font-normal text-gray-400 ml-1">({{ count($altList) }})</span>
                </h3>
                <div class="space-y-2">
                    @foreach($altList as $alt)
                        <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-2">
                            <div>
                                <span class="font-mono text-sm font-semibold text-gray-800">
                                    {{ $alt['article_nr'] ?? $alt['art_article_nr'] ?? '—' }}
                                </span>
                                <span class="text-xs text-gray-400 ml-2">{{ $alt['brand'] ?? '' }}</span>
                            </div>
                            @if(!empty($alt['art_id']))
                                <a href="{{ route('auto-parts.article', $alt['art_id']) }}"
                                   class="text-xs text-blue-500 hover:underline">View →</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="text-sm">
            <a href="javascript:history.back()" class="text-gray-400 hover:text-blue-500 transition">← Back</a>
        </div>

    </div>
</div>
@endsection
