@extends('catalog::layouts.catalog')

@section('title', 'Search Parts')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        @if($query) Search results for <span class="text-blue-600 font-mono">{{ $query }}</span>
        @else Search Parts
        @endif
    </h1>
</div>

{{-- Search form --}}
<div class="bg-white border border-gray-100 rounded-2xl p-5 mb-6">
    <form action="{{ route('auto-parts.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="q" value="{{ $query }}"
               placeholder="Article number, OEM number, EAN..."
               class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
        <select name="type" class="sm:w-36 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="ART"  {{ $type === 'ART'  ? 'selected' : '' }}>Article</option>
            <option value="OEM"  {{ $type === 'OEM'  ? 'selected' : '' }}>OEM number</option>
            <option value="EAN"  {{ $type === 'EAN'  ? 'selected' : '' }}>EAN / barcode</option>
        </select>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
            Search
        </button>
    </form>
</div>

{{-- Results --}}
@if($query && empty($results['data']))
    <p class="text-gray-400 text-sm">No results found for "{{ $query }}".</p>
@elseif(!empty($results['data']))
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($results['data'] as $item)
            @php $artId = $item['art_id'] ?? $item['id'] ?? null; @endphp
            <a href="{{ $artId ? route('auto-parts.article', $artId) : '#' }}"
               class="bg-white border border-gray-100 rounded-xl p-4 hover:border-blue-300 hover:shadow-sm transition group">
                <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">
                    {{ $item['mfa_brand'] ?? $item['brand'] ?? '' }}
                </div>
                <div class="font-mono font-semibold text-gray-800 group-hover:text-blue-600">
                    {{ $item['art_article_nr'] ?? $item['article_number'] ?? '—' }}
                </div>
                <div class="text-sm text-gray-500 mt-1 truncate">
                    {{ $item['art_complete_designation'] ?? $item['name'] ?? '' }}
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
