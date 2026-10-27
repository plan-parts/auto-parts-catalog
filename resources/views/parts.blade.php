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
</div>

@if(!empty($parts['data']))
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($parts['data'] as $part)
            @php $artId = $part['art_id'] ?? $part['id'] ?? null; @endphp
            <a href="{{ $artId ? route('auto-parts.article', $artId) : '#' }}"
               class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:border-blue-300 hover:shadow-sm transition group">

                {{-- Image --}}
                @if(!empty($part['image_url']) || !empty($part['img']))
                    <div class="h-36 bg-gray-50 flex items-center justify-center overflow-hidden">
                        <img src="{{ $part['image_url'] ?? $part['img'] }}"
                             alt="{{ $part['art_article_nr'] ?? '' }}"
                             class="max-h-full max-w-full object-contain p-3">
                    </div>
                @endif

                <div class="p-4">
                    <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">
                        {{ $part['mfa_brand'] ?? $part['brand'] ?? '' }}
                    </div>
                    <div class="font-mono font-semibold text-gray-800 group-hover:text-blue-600">
                        {{ $part['art_article_nr'] ?? $part['article_number'] ?? '—' }}
                    </div>
                    <div class="text-sm text-gray-500 mt-1 truncate">
                        {{ $part['art_complete_designation'] ?? $part['name'] ?? '' }}
                    </div>
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
