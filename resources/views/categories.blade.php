@extends('catalog::layouts.catalog')

@section('title', 'Select Part Category')

@section('breadcrumbs')
    <span class="breadcrumb-arrow">Categories</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Part Categories</h1>
    <p class="text-gray-500 text-sm mt-1">Select a category to browse available parts.</p>
</div>

@if(!empty($categories['data']))
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($categories['data'] as $cat)
            <a href="{{ route('auto-parts.parts', [$ktype, $cat['pt_id'] ?? $cat['id']]) }}"
               class="bg-white border border-gray-100 rounded-xl px-4 py-3 hover:border-blue-300 hover:shadow-sm transition group flex items-center justify-between">
                <div>
                    <div class="font-semibold text-gray-700 group-hover:text-blue-600">
                        {{ $cat['pt_name'] ?? $cat['name'] ?? '—' }}
                    </div>
                    @if(!empty($cat['pt_count']))
                        <div class="text-xs text-gray-400 mt-0.5">{{ $cat['pt_count'] }} parts</div>
                    @endif
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endforeach
    </div>
@else
    <p class="text-gray-400">No categories found for this vehicle.</p>
@endif
@endsection
