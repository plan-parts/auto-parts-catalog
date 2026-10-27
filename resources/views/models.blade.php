@extends('catalog::layouts.catalog')

@section('title', 'Select Model')

@section('breadcrumbs')
    <span class="breadcrumb-arrow">Models</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Select Model</h1>
</div>

@php $items = $models['data']['results'] ?? []; @endphp

@if(!empty($items))
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($items as $model)
            <a href="{{ route('auto-parts.modifications', $model['ms_id']) }}"
               class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:border-blue-300 hover:shadow-sm transition group flex">
                @if(!empty($model['photo_url'] ?? $model['image_url'] ?? null))
                    <div class="w-20 flex-shrink-0 bg-gray-50 flex items-center justify-center overflow-hidden">
                        <img src="{{ $model['photo_url'] ?? $model['image_url'] }}"
                             alt="{{ $model['name'] }}"
                             class="w-full h-full object-cover opacity-80 group-hover:opacity-100">
                    </div>
                @endif
                <div class="px-4 py-3 flex flex-col justify-center min-w-0">
                    <div class="font-semibold text-gray-700 group-hover:text-blue-600 truncate">
                        {{ $model['name'] }}
                    </div>
                    @if(!empty($model['year_from']))
                        <div class="text-xs text-gray-400 mt-0.5">
                            {{ $model['year_from'] }}{{ !empty($model['year_to']) ? ' – ' . $model['year_to'] : '+' }}
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@else
    <p class="text-gray-400">No models found.</p>
@endif
@endsection
