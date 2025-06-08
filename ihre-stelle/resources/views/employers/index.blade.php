@extends('layouts.app')

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@push('meta')
    <!-- Open Graph Tags -->
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('employers.index') }}">
    <meta property="og:image" content="{{ asset('logo/ihre-stelle_logo_quer-logo.png') }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ asset('logo/ihre-stelle_logo_quer-logo.png') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('employers.index') }}">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "Alle Arbeitgeber",
        "description": "{{ $metaDescription }}",
        "url": "{{ route('employers.index') }}",
        "mainEntity": {
            "@type": "ItemList",
            "numberOfItems": {{ $employers->count() }},
            "itemListElement": [
                @foreach($employers->take(10) as $index => $employer)
                {
                    "@type": "ListItem",
                    "position": {{ $index + 1 }},
                    "item": {
                        "@type": "Organization",
                        "name": "{{ $employer->arbeitsgeber_name }}",
                        "url": "{{ route('employers.show', $employer->slug) }}",
                        "jobPosting": {
                            "@type": "QuantitativeValue",
                            "value": {{ $employer->job_count }}
                        }
                    }
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
    }
    </script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb Navigation -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">Home</a></li>
            <li class="before:content-['/'] before:mx-2 text-gray-900 font-medium">
                Arbeitgeber
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Alle Arbeitgeber
        </h1>
        <p class="text-xl text-gray-600 mb-6">
            Entdecken Sie {{ $employers->count() }} Unternehmen mit aktuellen Stellenangeboten
        </p>
        
        <div class="flex justify-center">
            <div class="bg-blue-50 rounded-lg p-6 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $employers->sum('job_count') }}</div>
                <div class="text-gray-700">Gesamte offene Stellen</div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="employerSearch" placeholder="Arbeitgeber suchen..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-2">
                <select id="sortBy" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="jobs">Nach Stellenanzahl</option>
                    <option value="name">Nach Name</option>
                    <option value="locations">Nach Standorten</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Top Employers -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Top-Arbeitgeber</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($employers->take(6) as $employer)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-start gap-4">
                    @php
                        $logoUrl = null;
                        if ($employer->info_fuer_uns && str_contains($employer->info_fuer_uns, 'job-logos/')) {
                            $logoUrl = asset('storage/' . $employer->info_fuer_uns);
                        } elseif ($employer->job_logo && is_array($employer->job_logo) && !empty($employer->job_logo)) {
                            $logoUrl = $employer->job_logo[0]['url'] ?? null;
                        }
                    @endphp
                    
                    @if($logoUrl)
                    <div class="flex-shrink-0">
                        <img src="{{ $logoUrl }}" alt="{{ $employer->arbeitsgeber_name }} Logo" 
                             class="w-16 h-16 object-contain rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('employers.show', $employer->slug) }}" 
                               class="hover:text-blue-600 transition-colors">
                                {{ $employer->arbeitsgeber_name }}
                            </a>
                        </h3>
                        
                        <div class="flex flex-wrap gap-2 text-sm text-gray-600 mb-3">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                {{ $employer->job_count }} {{ $employer->job_count == 1 ? 'Job' : 'Jobs' }}
                            </span>
                            @if($employer->primary_city)
                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                {{ $employer->primary_city }}
                            </span>
                            @endif
                        </div>
                        
                        <a href="{{ route('employers.show', $employer->slug) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Alle Stellen ansehen →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- All Employers List -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Alle Arbeitgeber</h2>
        
        <div id="employersList" class="space-y-4">
            @foreach($employers as $employer)
            <div class="employer-item bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow" 
                 data-name="{{ strtolower($employer->arbeitsgeber_name) }}" 
                 data-jobs="{{ $employer->job_count }}"
                 data-city="{{ strtolower($employer->primary_city ?? '') }}">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        @php
                            $logoUrl = null;
                            if ($employer->info_fuer_uns && str_contains($employer->info_fuer_uns, 'job-logos/')) {
                                $logoUrl = asset('storage/' . $employer->info_fuer_uns);
                            } elseif ($employer->job_logo && is_array($employer->job_logo) && !empty($employer->job_logo)) {
                                $logoUrl = $employer->job_logo[0]['url'] ?? null;
                            }
                        @endphp
                        
                        @if($logoUrl)
                        <div class="flex-shrink-0">
                            <img src="{{ $logoUrl }}" alt="{{ $employer->arbeitsgeber_name }} Logo" 
                                 class="w-12 h-12 object-contain rounded border border-gray-200">
                        </div>
                        @endif
                        
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-1">
                                <a href="{{ route('employers.show', $employer->slug) }}" 
                                   class="hover:text-blue-600 transition-colors">
                                    {{ $employer->arbeitsgeber_name }}
                                </a>
                            </h3>
                            @if($employer->primary_city)
                            <p class="text-gray-600">{{ $employer->primary_city }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $employer->job_count }}</div>
                            <div class="text-sm text-gray-500">{{ $employer->job_count == 1 ? 'Job' : 'Jobs' }}</div>
                        </div>
                        
                        <a href="{{ route('employers.show', $employer->slug) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            Jobs ansehen
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Statistics -->
    <section class="mt-12 bg-gray-50 rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Statistiken</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $employers->count() }}</div>
                <div class="text-gray-700">Arbeitgeber</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $employers->sum('job_count') }}</div>
                <div class="text-gray-700">Offene Stellen</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($employers->avg('job_count'), 1) }}</div>
                <div class="text-gray-700">⌀ Jobs pro Arbeitgeber</div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('employerSearch');
    const sortSelect = document.getElementById('sortBy');
    const employersList = document.getElementById('employersList');
    const employerItems = Array.from(document.querySelectorAll('.employer-item'));

    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const sortBy = sortSelect.value;
        
        // Filter
        const filteredItems = employerItems.filter(item => {
            const name = item.dataset.name;
            const city = item.dataset.city;
            return name.includes(searchTerm) || city.includes(searchTerm);
        });
        
        // Sort
        filteredItems.sort((a, b) => {
            switch(sortBy) {
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'jobs':
                    return parseInt(b.dataset.jobs) - parseInt(a.dataset.jobs);
                case 'locations':
                    return a.dataset.city.localeCompare(b.dataset.city);
                default:
                    return parseInt(b.dataset.jobs) - parseInt(a.dataset.jobs);
            }
        });
        
        // Clear and re-append
        employersList.innerHTML = '';
        filteredItems.forEach(item => employersList.appendChild(item));
        
        // Hide non-matching items
        employerItems.forEach(item => {
            item.style.display = filteredItems.includes(item) ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterAndSort);
    sortSelect.addEventListener('change', filterAndSort);
});
</script>
@endsection 