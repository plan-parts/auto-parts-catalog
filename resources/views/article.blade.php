@extends('catalog::layouts.catalog')

@section('title', ($article['data']['art_article_nr'] ?? 'Part Detail'))

@section('content')
@php
    $data     = $article['data']    ?? [];
    $images   = $media['data']      ?? [];
    $specs    = $criteria['data']   ?? [];
    $oem      = $crosses['data']    ?? [];
    $altParts = $analogs['data']    ?? [];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: image + specs --}}
    <div class="space-y-4">
        {{-- Image --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-4 flex items-center justify-center min-h-48">
            @if(!empty($images[0]['image_url'] ?? $images[0]['img'] ?? null))
                <img src="{{ $images[0]['image_url'] ?? $images[0]['img'] }}"
                     alt="{{ $data['art_article_nr'] ?? '' }}"
                     class="max-h-56 object-contain">
            @else
                <div class="text-gray-300 text-sm">No image available</div>
            @endif
        </div>

        {{-- Technical specs --}}
        @if(!empty($specs))
            <div class="bg-white border border-gray-100 rounded-2xl p-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Specifications</h3>
                <div class="space-y-2">
                    @foreach($specs as $spec)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ $spec['crt_description'] ?? $spec['name'] ?? '' }}</span>
                            <span class="text-gray-800 font-medium">{{ $spec['crt_value'] ?? $spec['value'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Right: main info --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Main card --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6">
            <div class="text-xs text-gray-400 uppercase tracking-widest mb-1">
                {{ $data['mfa_brand'] ?? $data['brand']['name'] ?? '' }}
            </div>
            <h1 class="text-2xl font-bold font-mono text-gray-800">
                {{ $data['art_article_nr'] ?? $data['article_number'] ?? '—' }}
            </h1>
            <p class="text-gray-500 mt-2">
                {{ $data['art_complete_designation'] ?? $data['name'] ?? '' }}
            </p>
        </div>

        {{-- OEM cross-references --}}
        @if(!empty($oem))
            <div class="bg-white border border-gray-100 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    OEM / Cross-Reference Numbers
                    <span class="text-xs font-normal text-gray-400 ml-1">({{ count($oem) }})</span>
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($oem as $cross)
                        <span class="bg-gray-50 border border-gray-200 text-xs font-mono px-2 py-1 rounded">
                            {{ $cross['oe_number'] ?? $cross['article_number'] ?? $cross['number'] ?? '?' }}
                            @if(!empty($cross['mfa_name'] ?? $cross['brand'] ?? null))
                                <span class="text-gray-400 ml-1">{{ $cross['mfa_name'] ?? $cross['brand'] }}</span>
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Analogs --}}
        @if(!empty($altParts))
            <div class="bg-white border border-gray-100 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Analog Parts
                    <span class="text-xs font-normal text-gray-400 ml-1">({{ count($altParts) }})</span>
                </h3>
                <div class="space-y-2">
                    @foreach($altParts as $alt)
                        @php $altId = $alt['art_id'] ?? $alt['id'] ?? null; @endphp
                        <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-2">
                            <div>
                                <span class="font-mono text-sm font-semibold text-gray-800">
                                    {{ $alt['art_article_nr'] ?? $alt['article_number'] ?? $alt['article'] ?? '—' }}
                                </span>
                                <span class="text-xs text-gray-400 ml-2">{{ $alt['mfa_brand'] ?? $alt['brand'] ?? '' }}</span>
                            </div>
                            @if($altId)
                                <a href="{{ route('auto-parts.article', $altId) }}"
                                   class="text-xs text-blue-500 hover:underline">View →</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Back link --}}
        <div class="text-sm">
            <a href="javascript:history.back()" class="text-gray-400 hover:text-blue-500 transition">← Back</a>
        </div>

    </div>
</div>
@endsection
