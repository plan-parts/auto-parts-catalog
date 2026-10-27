@extends('catalog::layouts.catalog')

@section('title', 'Select Vehicle Brand')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Select Vehicle Brand</h1>
    <p class="text-gray-500 text-sm mt-1">Choose your vehicle manufacturer to browse OEM parts.</p>
</div>

@if(!empty($brands['data']))
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
        @foreach($brands['data'] as $brand)
            <a href="{{ route('auto-parts.models', $brand['mfa_id']) }}"
               class="bg-white border border-gray-100 rounded-xl px-3 py-4 text-center hover:border-blue-300 hover:shadow-sm transition group">
                <div class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 truncate">
                    {{ $brand['mfa_name'] ?? $brand['name'] ?? '—' }}
                </div>
            </a>
        @endforeach
    </div>
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
