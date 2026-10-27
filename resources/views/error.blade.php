@extends('catalog::layouts.catalog')

@section('title', 'Error')

@section('content')
<div class="bg-red-50 border border-red-100 rounded-2xl p-8 text-center max-w-lg mx-auto mt-8">
    <h2 class="text-lg font-semibold text-red-700 mb-2">API Error</h2>
    <p class="text-sm text-red-600">{{ $message }}</p>
    @if(str_contains($message ?? '', 'token') || str_contains($message ?? '', 'Token'))
        <p class="mt-4 text-sm text-gray-600">
            <a href="https://plan.parts/register" target="_blank"
               class="text-blue-600 font-semibold hover:underline">
                Register at plan.parts to get your API token →
            </a>
        </p>
    @endif
    <a href="{{ route('auto-parts.index') }}" class="inline-block mt-4 text-sm text-gray-500 hover:text-blue-500">
        ← Back to catalog
    </a>
</div>
@endsection
