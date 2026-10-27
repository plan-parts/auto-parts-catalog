@extends('catalog::layouts.catalog')

@section('title', 'Parts List')

@section('breadcrumbs')
    <span class="breadcrumb-arrow">
        <a href="{{ route('auto-parts.categories', $ktype) }}" class="hover:text-blue-600">Categories</a>
    </span>
    <span class="breadcrumb-arrow">Parts</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Available Parts</h1>
    @php $total = $parts['data']['count'] ?? 0; @endphp
    @if($total)
        <p class="text-gray-400 text-sm mt-1">{{ number_format($total) }} parts found</p>
    @endif
</div>

@php $items = $parts['data']['results'] ?? []; @endphp

@if(!empty($items))
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach($items as $part)
            <a href="{{ !empty($part['art_id']) ? route('auto-parts.article', $part['art_id']) : '#' }}"
               class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:border-blue-300 hover:shadow-sm transition group {{ empty($part['art_id']) ? 'pointer-events-none opacity-60' : '' }}">

                <div class="h-32 bg-gray-50 flex items-center justify-center overflow-hidden">
                    @if(!empty($part['img_url']))
                        <img src="{{ $part['img_url'] }}" alt="{{ $part['article_nr'] }}"
                             class="max-h-full max-w-full object-contain p-2">
                    @else
                        <svg class="w-8 h-8 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                        </svg>
                    @endif
                </div>

                <div class="p-3">
                    <div class="text-xs text-gray-400 truncate">{{ $part['brand'] ?? '' }}</div>
                    <div class="font-mono font-semibold text-gray-800 group-hover:text-blue-600 text-sm truncate">
                        {{ $part['article_nr'] ?? '—' }}
                    </div>
                    @if(!empty($part['part_name'] ?? $part['product_name'] ?? null))
                        <div class="text-xs text-gray-500 mt-1 line-clamp-2 leading-snug">
                            {{ $part['part_name'] ?? $part['product_name'] }}
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="text-center py-16 text-gray-400">
        <p>No parts found for this vehicle and category.</p>
    </div>
@endif
@endsection
