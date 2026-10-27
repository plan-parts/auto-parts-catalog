@extends('catalog::layouts.catalog')

@section('title', 'Select Vehicle Brand')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Select Vehicle Brand</h1>
    <p class="text-gray-500 text-sm mt-1">Choose your vehicle manufacturer to browse OEM parts.</p>
</div>

@php $items = $brands['data']['results'] ?? []; @endphp

@if(!empty($items))
    @php
        $grouped = collect($items)
            ->groupBy(fn($b) => strtoupper(substr($b['brand'] ?? '#', 0, 1)))
            ->sortKeys();
    @endphp

    @foreach($grouped as $letter => $group)
        <div class="mb-6">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ $letter }}</div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                @foreach($group as $brand)
                    <a href="{{ route('auto-parts.models', $brand['mfa_id']) }}"
                       class="bg-white border border-gray-100 rounded-xl px-3 py-3 flex flex-col items-center gap-2 hover:border-blue-300 hover:shadow-sm transition group">
                        @if(!empty($brand['logo_url']))
                            <img src="{{ $brand['logo_url'] }}" alt="{{ $brand['brand'] }}"
                                 class="h-6 object-contain opacity-70 group-hover:opacity-100">
                        @endif
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600 truncate w-full text-center">
                            {{ $brand['brand'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
@else
    <div class="text-center py-16 text-gray-400">
        <p>No brands found. Check your API token configuration.</p>
        <p class="mt-2 text-sm">
            <a href="https://plan.parts/register" target="_blank" class="text-blue-500 hover:underline">
                Register at plan.parts →
            </a>
        </p>
    </div>
@endif
@endsection
