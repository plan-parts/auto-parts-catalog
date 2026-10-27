@extends('catalog::layouts.catalog')

@section('title', 'Select Model')

@section('breadcrumbs')
    <span class="breadcrumb-arrow">Models</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Select Model</h1>
</div>

@if(!empty($models['data']))
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($models['data'] as $model)
            <a href="{{ route('auto-parts.modifications', $model['ms_id']) }}"
               class="bg-white border border-gray-100 rounded-xl px-4 py-3 hover:border-blue-300 hover:shadow-sm transition group flex items-center justify-between">
                <div>
                    <div class="font-semibold text-gray-700 group-hover:text-blue-600">
                        {{ $model['ms_name'] ?? $model['name'] ?? '—' }}
                    </div>
                    @if(!empty($model['ms_date_of_const_from']))
                        <div class="text-xs text-gray-400 mt-0.5">
                            {{ $model['ms_date_of_const_from'] }}
                            @if(!empty($model['ms_date_of_const_to']))
                                – {{ $model['ms_date_of_const_to'] }}
                            @endif
                        </div>
                    @endif
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endforeach
    </div>
@else
    <p class="text-gray-400">No models found.</p>
@endif
@endsection
