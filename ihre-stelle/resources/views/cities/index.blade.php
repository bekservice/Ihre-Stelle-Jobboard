@extends('layouts.app')

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@push('meta')
    <!-- Open Graph Tags -->
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('cities.index') }}">
    <meta property="og:image" content="{{ asset('logo/ihre-stelle_logo_quer-logo.png') }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ asset('logo/ihre-stelle_logo_quer-logo.png') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('cities.index') }}">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "Jobs nach Städten",
        "description": "{{ $metaDescription }}",
        "url": "{{ route('cities.index') }}",
        "mainEntity": {
            "@type": "ItemList",
            "numberOfItems": {{ $cities->count() }},
            "itemListElement": [
                @foreach($cities->take(10) as $index => $city)
                {
                    "@type": "ListItem",
                    "position": {{ $index + 1 }},
                    "item": {
                        "@type": "Place",
                        "name": "{{ $city->city }}",
                        "url": "{{ route('cities.show', $city->slug) }}",
                        "address": {
                            "@type": "PostalAddress",
                            "addressLocality": "{{ $city->city }}",
                            @if($city->postal_code)
                            "postalCode": "{{ $city->postal_code }}",
                            @endif
                            "addressCountry": "{{ $city->country ?: 'DE' }}"
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
                Städte
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Jobs nach Städten
        </h1>
        <p class="text-xl text-gray-600 mb-6">
            Finden Sie Stellenangebote in {{ $cities->count() }} Städten in Deutschland
        </p>
        
        <div class="flex justify-center">
            <div class="bg-blue-50 rounded-lg p-6 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $cities->sum('job_count') }}</div>
                <div class="text-gray-700">Gesamte offene Stellen</div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="citySearch" placeholder="Stadt oder PLZ suchen..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-2">
                <select id="sortBy" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="jobs">Nach Stellenanzahl</option>
                    <option value="name">Nach Name</option>
                    <option value="postal">Nach PLZ</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Top Cities -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Top-Städte</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($cities->take(6) as $city)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <a href="{{ route('cities.show', $city->slug) }}" 
                           class="hover:text-blue-600 transition-colors">
                            {{ $city->city }}
                        </a>
                    </h3>
                    
                    @if($city->postal_code)
                    <p class="text-gray-600 mb-4">PLZ: {{ $city->postal_code }}</p>
                    @endif
                    
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium mb-4">
                        {{ $city->job_count }} {{ $city->job_count == 1 ? 'Job' : 'Jobs' }}
                    </div>
                    
                    <a href="{{ route('cities.show', $city->slug) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Alle Jobs ansehen →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Cities by Letter -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Alle Städte</h2>
        
        <!-- Alphabet Navigation -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-wrap gap-2 justify-center">
                @foreach($citiesByLetter->keys() as $letter)
                <a href="#letter-{{ $letter }}" 
                   class="px-3 py-1 bg-gray-100 hover:bg-blue-100 rounded text-sm font-medium transition-colors">
                    {{ $letter }}
                </a>
                @endforeach
            </div>
        </div>
        
        <!-- Cities List -->
        <div id="citiesList">
            @foreach($citiesByLetter as $letter => $letterCities)
            <div id="letter-{{ $letter }}" class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                    {{ $letter }}
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($letterCities as $city)
                    <div class="city-item bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow" 
                         data-name="{{ strtolower($city->city) }}" 
                         data-jobs="{{ $city->job_count }}"
                         data-postal="{{ $city->postal_code ?? '' }}">
                        
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                    <a href="{{ route('cities.show', $city->slug) }}" 
                                       class="hover:text-blue-600 transition-colors">
                                        {{ $city->city }}
                                    </a>
                                </h4>
                                @if($city->postal_code)
                                <p class="text-sm text-gray-600">PLZ: {{ $city->postal_code }}</p>
                                @endif
                            </div>
                            
                            <div class="text-center">
                                <div class="text-xl font-bold text-blue-600">{{ $city->job_count }}</div>
                                <div class="text-xs text-gray-500">{{ $city->job_count == 1 ? 'Job' : 'Jobs' }}</div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('cities.show', $city->slug) }}" 
                               class="w-full bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition-colors block text-center">
                                Jobs ansehen
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Statistics -->
    <section class="mt-12 bg-gray-50 rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Statistiken</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $cities->count() }}</div>
                <div class="text-gray-700">Städte</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $cities->sum('job_count') }}</div>
                <div class="text-gray-700">Offene Stellen</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($cities->avg('job_count'), 1) }}</div>
                <div class="text-gray-700">⌀ Jobs pro Stadt</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">{{ $cities->where('job_count', '>', 10)->count() }}</div>
                <div class="text-gray-700">Städte mit 10+ Jobs</div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="mt-12 bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            Häufig gestellte Fragen zu Jobs in Deutschland
        </h2>
        
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    In welchen Städten gibt es die meisten Jobs?
                </h3>
                <p class="text-gray-700">
                    Die Städte mit den meisten Stellenangeboten sind: 
                    {{ $cities->take(5)->pluck('city')->implode(', ') }}.
                    @if($cities->first())
                        {{ $cities->first()->city }} führt mit {{ $cities->first()->job_count }} offenen Stellen.
                    @endif
                </p>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Wie finde ich Jobs in meiner Stadt?
                </h3>
                <p class="text-gray-700">
                    Nutzen Sie unsere Suchfunktion oben oder klicken Sie direkt auf Ihre Stadt in der Liste. 
                    Sie können auch nach Postleitzahl suchen, um Jobs in Ihrer Nähe zu finden.
                </p>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Werden regelmäßig neue Städte hinzugefügt?
                </h3>
                <p class="text-gray-700">
                    Ja, unsere Städteliste wird automatisch erweitert, sobald neue Stellenangebote in weiteren Orten verfügbar sind. 
                    Derzeit haben wir Jobs in {{ $cities->count() }} verschiedenen Städten.
                </p>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('citySearch');
    const sortSelect = document.getElementById('sortBy');
    const cityItems = Array.from(document.querySelectorAll('.city-item'));

    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const sortBy = sortSelect.value;
        
        // Filter
        cityItems.forEach(item => {
            const name = item.dataset.name;
            const postal = item.dataset.postal.toLowerCase();
            const matches = name.includes(searchTerm) || postal.includes(searchTerm);
            item.style.display = matches ? 'block' : 'none';
            
            // Hide/show parent letter sections
            const letterSection = item.closest('[id^="letter-"]');
            if (letterSection) {
                const visibleItems = letterSection.querySelectorAll('.city-item[style="display: block"], .city-item:not([style*="display: none"])');
                letterSection.style.display = visibleItems.length > 0 ? 'block' : 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterAndSort);
    
    // Smooth scrolling for alphabet navigation
    document.querySelectorAll('a[href^="#letter-"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
</script>
@endsection 