@extends('catalog::layouts.catalog')

@section('title', 'Search Parts')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        @if($query)
            Results for <span class="text-blue-600 font-mono">{{ $query }}</span>
        @else
            Search Parts
        @endif
    </h1>
    @if($query && !empty($results['data']['count']))
        <p class="text-gray-400 text-sm mt-1">{{ number_format($results['data']['count']) }} results found</p>
    @endif
</div>

{{-- Search form --}}
<div class="bg-white border border-gray-100 rounded-2xl p-5 mb-6">
    <form action="{{ route('auto-parts.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="q" value="{{ $query }}"
               placeholder="Article number, OEM number, EAN..."
               class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
               autofocus>
        <select name="type" class="sm:w-36 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="ART" {{ $type === 'ART' ? 'selected' : '' }}>Article</option>
            <option value="OEM" {{ $type === 'OEM' ? 'selected' : '' }}>OEM number</option>
            <option value="EAN" {{ $type === 'EAN' ? 'selected' : '' }}>EAN / barcode</option>
        </select>
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
            Search
        </button>
    </form>
</div>

@php $items = $results['data']['results'] ?? []; @endphp

@if($query && empty($items))
    <p class="text-gray-400 text-sm">No results found for "{{ $query }}".</p>
@elseif(!empty($items))
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach($items as $item)
            <a href="{{ !empty($item['art_id']) ? route('auto-parts.article', $item['art_id']) : '#' }}"
               class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:border-blue-300 hover:shadow-sm transition group {{ empty($item['art_id']) ? 'pointer-events-none opacity-60' : '' }}">

                <div class="h-28 bg-gray-50 flex items-center justify-center overflow-hidden">
                    @if(!empty($item['img_url']))
                        <img src="{{ $item['img_url'] }}" alt="{{ $item['article_nr'] }}"
                             class="max-h-full max-w-full object-contain p-2">
                    @else
                        <svg class="w-8 h-8 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16"/>
                        </svg>
                    @endif
                </div>

                <div class="p-3">
                    <div class="text-xs text-gray-400 truncate">{{ $item['brand'] ?? '' }}</div>
                    <div class="font-mono font-semibold text-gray-800 group-hover:text-blue-600 text-sm truncate">
                        {{ $item['article_nr'] ?? '—' }}
                    </div>
                    @if(!empty($item['part_name'] ?? $item['product_name'] ?? null))
                        <div class="text-xs text-gray-500 mt-1 line-clamp-2 leading-snug">
                            {{ $item['part_name'] ?? $item['product_name'] }}
                        </div>
                    @endif
                    @if(!empty($item['match_type']))
                        <span class="inline-block mt-1 text-xs bg-green-50 text-green-600 px-1.5 py-0.5 rounded">
                            {{ $item['match_type'] }}
                        </span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
