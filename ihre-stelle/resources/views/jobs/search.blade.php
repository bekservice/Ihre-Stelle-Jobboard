@extends('layouts.app')

@section('title', 'Jobs suchen - Ihre Stelle')
@section('description', 'Durchsuchen Sie tausende aktuelle Stellenanzeigen. Finden Sie Ihren Traumjob mit unseren erweiterten Suchfiltern.')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Search Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Jobs suchen</h1>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('jobs.search') }}" id="job-search-form" class="search-container">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700 mb-2">Suchbegriff</label>
                        <input type="text" 
                               id="q" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Job, Unternehmen, Ort..."
                               class="search-input w-full">
                    </div>
                    
                    <div class="relative">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Standort</label>
                        <input type="text" 
                               id="location" 
                               name="location" 
                               value="{{ request('location') }}"
                               placeholder="Stadt, PLZ oder Adresse..."
                               class="search-input w-full"
                               autocomplete="off">
                        <div id="location-suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategorie</label>
                        <select name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Alle Kategorien</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="job_type" class="block text-sm font-medium text-gray-700 mb-2">Arbeitszeit</label>
                        <select name="job_type" id="job_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Alle Arbeitszeiten</option>
                            @foreach($jobTypes as $jobType)
                                <option value="{{ $jobType }}" {{ request('job_type') == $jobType ? 'selected' : '' }}>
                                    {{ is_string($jobType) ? $jobType : (is_array($jobType) ? implode(', ', $jobType) : '') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="search-button">
                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Suchen
                    </button>
                    <a href="{{ route('jobs.search') }}" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors text-center">
                        Filter zurücksetzen
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Results -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Results Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ $jobs->total() }} Jobs gefunden
                </h2>
                @if(request()->hasAny(['q', 'location', 'category', 'job_type']))
                    <p class="text-sm text-gray-600 mt-1">
                        @if(request('q'))
                            für "{{ request('q') }}"
                        @endif
                        @if(request('location'))
                            in {{ request('location') }}
                        @endif
                        @if(request('category'))
                            in {{ request('category') }}
                        @endif
                        @if(request('job_type'))
                            ({{ request('job_type') }})
                        @endif
                    </p>
                @endif
            </div>
            
            <div class="flex items-center space-x-2">
                <label for="sort" class="text-sm text-gray-600">Sortieren:</label>
                <select id="sort" class="text-sm border border-gray-300 rounded px-2 py-1">
                    <option>Neueste zuerst</option>
                    <option>Relevanz</option>
                    <option>Unternehmen A-Z</option>
                </select>
            </div>
        </div>

        @if($jobs->count() > 0)
            <!-- Map and List Toggle -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <button id="list-view-btn" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Liste
                    </button>
                    <button id="map-view-btn" class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium">
                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        Karte
                    </button>
                </div>
            </div>

            <!-- Map View -->
            <div id="map-view" class="hidden mb-8">
                <x-job-map :jobs="$jobs" height="500px" />
            </div>

            <!-- Job Listings -->
            <div id="list-view" class="space-y-4 mb-8">
                @foreach($jobs as $job)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            <a href="{{ route('jobs.show', $job) }}" class="hover:text-blue-600 transition-colors">
                                                {{ $job->title }}
                                            </a>
                                        </h3>
                                        @if($job->arbeitsgeber_name)
                                            <p class="text-gray-700 font-medium mb-2">{{ $job->arbeitsgeber_name }}</p>
                                        @endif
                                    </div>
                                    @if($job->info_fuer_uns && Storage::disk('public')->exists($job->info_fuer_uns))
                                        <img src="{{ asset('storage/' . $job->info_fuer_uns) }}" 
                                             alt="Logo von {{ $job->arbeitsgeber_name ?? 'Unternehmen' }}" 
                                             class="w-12 h-12 rounded-lg object-cover ml-4">
                                    @elseif($job->job_logo)
                                        @php
                                            $logoUrl = '';
                                            if (is_array($job->job_logo) && count($job->job_logo) > 0) {
                                                $logoUrl = $job->job_logo[0]['url'] ?? '';
                                            } elseif (is_string($job->job_logo)) {
                                                $logoUrl = $job->job_logo;
                                            }
                                        @endphp
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" 
                                                 alt="Logo von {{ $job->arbeitsgeber_name ?? 'Unternehmen' }}" 
                                                 class="w-12 h-12 rounded-lg object-cover ml-4">
                                        @endif
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                    @if($job->city)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $job->city }}@if($job->postal_code), {{ $job->postal_code }}@endif
                                        </div>
                                    @endif
                                    
                                    @if($job->job_type)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ is_array($job->job_type) ? implode(', ', $job->job_type) : $job->job_type }}
                                        </div>
                                    @endif
                                    
                                    @if($job->berufserfahrung)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                            </svg>
                                            {{ $job->berufserfahrung }}
                                        </div>
                                    @endif
                                    

                                    
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8"></path>
                                        </svg>
                                        {{ $job->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if($job->kategorie)
                                        <span class="job-badge">
                                            {{ $job->kategorie }}
                                        </span>
                                    @endif
                                    @if($job->autotags)
                                        @php
                                            $tags = is_array($job->autotags) ? $job->autotags : [$job->autotags];
                                        @endphp
                                        @foreach(array_slice($tags, 0, 3) as $tag)
                                            <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>

                                @if($job->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                        {{ Str::limit(strip_tags($job->formatted_description), 200) }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('jobs.show', $job) }}" 
                                           class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                                            Details ansehen
                                        </a>
                                        @if($job->bewerbung_an_mail)
                                            <a href="mailto:{{ $job->bewerbung_an_mail }}?subject=Bewerbung: {{ $job->title }}" 
                                               class="btn-outline px-4 py-2 rounded-lg text-sm font-medium">
                                                Bewerben
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <button class="text-gray-400 hover:text-gray-600 transition-colors" title="Job speichern">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                            </svg>
                                        </button>
                                        <button class="text-gray-400 hover:text-gray-600 transition-colors" title="Teilen">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $jobs->appends(request()->query())->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Jobs gefunden</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Versuchen Sie es mit anderen Suchbegriffen oder erweitern Sie Ihre Suchkriterien.
                </p>
                <div class="mt-6">
                    <a href="{{ route('jobs.search') }}" 
                       class="btn-primary inline-flex items-center px-4 py-2 rounded-md text-sm font-medium">
                        Alle Jobs anzeigen
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Map/List view toggle
    const listViewBtn = document.getElementById('list-view-btn');
    const mapViewBtn = document.getElementById('map-view-btn');
    const listView = document.getElementById('list-view');
    const mapView = document.getElementById('map-view');
    
    if (listViewBtn && mapViewBtn && listView && mapView) {
        listViewBtn.addEventListener('click', function() {
            listView.classList.remove('hidden');
            mapView.classList.add('hidden');
            
            // Update button styles
            listViewBtn.className = 'btn-primary px-4 py-2 rounded-lg text-sm font-medium';
            mapViewBtn.className = 'btn-secondary px-4 py-2 rounded-lg text-sm font-medium';
        });
        
        mapViewBtn.addEventListener('click', function() {
            listView.classList.add('hidden');
            mapView.classList.remove('hidden');
            
            // Update button styles
            listViewBtn.className = 'btn-secondary px-4 py-2 rounded-lg text-sm font-medium';
            mapViewBtn.className = 'btn-primary px-4 py-2 rounded-lg text-sm font-medium';
            
            // Trigger map resize after showing
            setTimeout(function() {
                if (window.map) {
                    window.map.resize();
                }
            }, 100);
        });
    }

    // Location autocomplete
    const locationInput = document.getElementById('location');
    const suggestionsContainer = document.getElementById('location-suggestions');
    let debounceTimer;

    if (locationInput) {
        locationInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();
            
            if (query.length < 2) {
                suggestionsContainer.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                searchLocations(query);
            }, 300);
        });

        locationInput.addEventListener('blur', function() {
            // Delay hiding to allow clicking on suggestions
            setTimeout(() => {
                suggestionsContainer.classList.add('hidden');
            }, 200);
        });

        locationInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                searchLocations(this.value.trim());
            }
        });
    }

    function searchLocations(query) {
        const accessToken = 'pk.eyJ1IjoiYmVrc2VydmljZSIsImEiOiJjazl2NnB3bjAwOG82M3BydWxtazQyczdkIn0.w_HtU8Vi9uRDtZa0Qy3FqA';
        const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${accessToken}&country=DE&types=place,postcode,locality,neighborhood&limit=5`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                displaySuggestions(data.features);
            })
            .catch(error => {
                console.error('Error fetching location suggestions:', error);
                suggestionsContainer.classList.add('hidden');
            });
    }

    function displaySuggestions(features) {
        if (features.length === 0) {
            suggestionsContainer.classList.add('hidden');
            return;
        }

        suggestionsContainer.innerHTML = '';
        
        features.forEach(feature => {
            const suggestion = document.createElement('div');
            suggestion.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
            
            const placeName = feature.place_name;
            const text = feature.text;
            
            suggestion.innerHTML = `
                <div class="font-medium text-gray-900">${text}</div>
                <div class="text-sm text-gray-500">${placeName}</div>
            `;
            
            suggestion.addEventListener('click', function() {
                locationInput.value = text;
                suggestionsContainer.classList.add('hidden');
                locationInput.focus();
            });
            
            suggestionsContainer.appendChild(suggestion);
        });
        
        suggestionsContainer.classList.remove('hidden');
    }
});
</script>
@endsection 