<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auto Parts Catalog') — plan.parts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .pp-gradient { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); }
        .breadcrumb-arrow::before { content: '/'; margin: 0 6px; color: #94a3b8; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-800">

{{-- Header --}}
<div class="pp-gradient text-white shadow-lg">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('auto-parts.index') }}" class="text-xl font-bold tracking-tight hover:text-blue-200 transition">
                Auto Parts Catalog
            </a>
            <span class="text-blue-300 text-sm hidden sm:block">powered by
                <a href="https://plan.parts" target="_blank" class="underline text-white">plan.parts</a>
            </span>
        </div>
        {{-- Search --}}
        <form action="{{ route('auto-parts.search') }}" method="GET" class="flex gap-2">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Search by article, OEM..."
                   class="w-48 sm:w-64 border-0 rounded-lg px-3 py-1.5 text-sm text-gray-800 focus:ring-2 focus:ring-white outline-none">
            <button type="submit"
                    class="bg-white text-blue-700 font-semibold px-3 py-1.5 rounded-lg text-sm hover:bg-blue-50 transition">
                Search
            </button>
        </form>
    </div>
</div>

{{-- Breadcrumbs --}}
@hasSection('breadcrumbs')
<div class="bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 py-2 text-sm text-gray-500 flex flex-wrap items-center">
        <a href="{{ route('auto-parts.index') }}" class="hover:text-blue-600">Brands</a>
        @yield('breadcrumbs')
    </div>
</div>
@endif

{{-- Content --}}
<div class="max-w-6xl mx-auto px-4 py-8">
    @yield('content')
</div>

{{-- Footer --}}
<div class="text-center text-xs text-gray-400 pb-6 space-y-1">
    <p>
        Powered by <a href="https://plan.parts" target="_blank" class="text-blue-500 hover:underline font-medium">plan.parts</a>
        Aftermarket API
    </p>
    <p>
        <a href="https://plan.parts/register" target="_blank" class="hover:underline">Get API Token</a> ·
        <a href="https://plan.parts/docs/aftermarket-oem-oe-iam" target="_blank" class="hover:underline">API Docs</a> ·
        <a href="https://plan.parts/support" target="_blank" class="hover:underline">Support</a>
    </p>
</div>

</body>
</html>
