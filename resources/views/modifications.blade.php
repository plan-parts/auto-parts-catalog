@extends('catalog::layouts.catalog')

@section('title', 'Select Modification')

@section('breadcrumbs')
    <span class="breadcrumb-arrow">Models</span>
    <span class="breadcrumb-arrow">Modifications</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Select Vehicle Modification</h1>
    <p class="text-gray-500 text-sm mt-1">Choose the exact engine and body variant.</p>
</div>

@if(!empty($modifications['data']))
    <div class="space-y-2">
        @foreach($modifications['data'] as $mod)
            <a href="{{ route('auto-parts.categories', $mod['ktype'] ?? $mod['ms_id']) }}"
               class="bg-white border border-gray-100 rounded-xl px-5 py-3 flex items-center justify-between hover:border-blue-300 hover:shadow-sm transition group">
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-700 group-hover:text-blue-600 truncate">
                        {{ $mod['ms_name'] ?? $mod['ms_fulltextname'] ?? $mod['name'] ?? '—' }}
                    </div>
                    <div class="flex flex-wrap gap-3 mt-1">
                        @if(!empty($mod['ms_motorcode']))
                            <span class="text-xs text-gray-400">Engine: {{ $mod['ms_motorcode'] }}</span>
                        @endif
                        @if(!empty($mod['ms_kw_from']))
                            <span class="text-xs text-gray-400">{{ $mod['ms_kw_from'] }} kW</span>
                        @endif
                        @if(!empty($mod['ms_ccm']))
                            <span class="text-xs text-gray-400">{{ $mod['ms_ccm'] }} cc</span>
                        @endif
                        @if(!empty($mod['ms_date_of_const_from']))
                            <span class="text-xs text-gray-400">
                                {{ $mod['ms_date_of_const_from'] }}
                                @if(!empty($mod['ms_date_of_const_to'])) – {{ $mod['ms_date_of_const_to'] }} @endif
                            </span>
                        @endif
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400 ml-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endforeach
    </div>
@else
    <p class="text-gray-400">No modifications found.</p>
@endif
@endsection
