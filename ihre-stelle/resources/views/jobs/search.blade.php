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
                @if($mapJobs->count() > 0)
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $mapJobs->count() }} Jobs mit Standortdaten werden auf der Karte angezeigt
                        </p>
                    </div>
                    <x-job-map :jobs="$mapJobs" height="500px" />
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Standortdaten verfügbar</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Für die gefundenen Jobs sind keine Koordinaten verfügbar, um sie auf der Karte anzuzeigen.
                        </p>
                    </div>
                @endif
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
                                        <button class="save-job-btn text-gray-400 hover:text-gray-600 transition-colors" 
                                                title="Job speichern" 
                                                data-job-id="{{ $job->id }}" 
                                                data-job-title="{{ $job->title }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                            </svg>
                                        </button>
                                        <button class="share-job-btn text-gray-400 hover:text-gray-600 transition-colors" 
                                                title="Teilen"
                                                data-job-url="{{ route('jobs.show', $job) }}"
                                                data-job-title="{{ $job->title }}"
                                                data-company-name="{{ $job->arbeitsgeber_name ?? '' }}">
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

    // Job speichern und teilen Funktionalität
    document.querySelectorAll('.save-job-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const jobId = this.dataset.jobId;
            const jobTitle = this.dataset.jobTitle;
            
            let savedJobs = JSON.parse(localStorage.getItem('savedJobs') || '[]');
            
            if (savedJobs.includes(jobId)) {
                savedJobs = savedJobs.filter(id => id !== jobId);
                localStorage.setItem('savedJobs', JSON.stringify(savedJobs));
                this.classList.remove('text-primary-orange');
                this.classList.add('text-gray-400');
                showNotification('Job aus gespeicherten Jobs entfernt', 'success');
            } else {
                savedJobs.push(jobId);
                localStorage.setItem('savedJobs', JSON.stringify(savedJobs));
                this.classList.remove('text-gray-400');
                this.classList.add('text-primary-orange');
                showNotification('Job erfolgreich gespeichert', 'success');
            }
        });
    });

    document.querySelectorAll('.share-job-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const jobUrl = this.dataset.jobUrl;
            const jobTitle = this.dataset.jobTitle;
            const companyName = this.dataset.companyName;
            
            showShareModal(jobUrl, jobTitle, companyName);
        });
    });

    // Prüfen welche Jobs bereits gespeichert sind
    function checkSavedJobs() {
        const savedJobs = JSON.parse(localStorage.getItem('savedJobs') || '[]');
        document.querySelectorAll('.save-job-btn').forEach(button => {
            const jobId = button.dataset.jobId;
            if (savedJobs.includes(jobId)) {
                button.classList.remove('text-gray-400');
                button.classList.add('text-primary-orange');
            }
        });
    }

    checkSavedJobs();
});

// Share Modal Funktionen
function showShareModal(jobUrl, jobTitle, companyName) {
    const shareText = `${jobTitle}${companyName ? ' bei ' + companyName : ''} - Jetzt bewerben!`;
    
    const modal = document.createElement('div');
    modal.className = 'share-modal';
    modal.innerHTML = `
        <div class="share-modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Job teilen</h3>
                <button id="close-share-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-3">
                <div class="share-option" onclick="shareViaWhatsApp('${encodeURIComponent(shareText)}', '${encodeURIComponent(jobUrl)}')">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.63"/>
                    </svg>
                    <span>WhatsApp</span>
                </div>
                
                <div class="share-option" onclick="shareViaEmail('${encodeURIComponent(shareText)}', '${encodeURIComponent(jobUrl)}')">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>E-Mail</span>
                </div>
                
                <div class="share-option" onclick="shareViaLinkedIn('${encodeURIComponent(jobUrl)}')">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    <span>LinkedIn</span>
                </div>
                
                <div class="share-option" onclick="shareViaXing('${encodeURIComponent(jobUrl)}')">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.188 0c-.517 0-.741.325-.927.66 0 0-7.455 13.224-7.702 13.657.284.52 4.56 8.668 4.56 8.668.19.34.411.66.927.66h3.905c.262 0 .442-.043.442-.252 0-.113-.07-.252-.442-.252 0 0-4.572-8.395-4.572-8.395s7.49-13.074 7.49-13.074c.367-.619.367-.619.367-.732 0-.21-.18-.252-.442-.252h-3.906zm-11.442 4.185c-.262 0-.44.04-.44.252 0 .113.178.252.44.252h2.861s-1.584 2.827-1.584 2.827c-.184.33-.184.66 0 .99 0 0 2.904 5.357 2.904 5.357s-3.67 6.544-3.67 6.544c-.190.34-.190.68 0 1.02.19.34.41.66.927.66h3.905c.262 0 .44-.043.44-.252 0-.113-.178-.252-.44-.252h-2.861s3.67-6.544 3.67-6.544c.184-.33.184-.66 0-.99 0 0-2.904-5.357-2.904-5.357s1.584-2.827 1.584-2.827c.190-.34.19-.68 0-1.02-.19-.34-.41-.66-.927-.66h-3.905z"/>
                    </svg>
                    <span>Xing</span>
                </div>
                
                <div class="share-option" onclick="copyToClipboard('${jobUrl}')">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span>Link kopieren</span>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Modal schließen
    document.getElementById('close-share-modal').addEventListener('click', function() {
        document.body.removeChild(modal);
    });
    
    // Modal schließen bei Klick außerhalb
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Share Funktionen (global verfügbar)
function shareViaWhatsApp(text, url) {
    window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
}

function shareViaEmail(subject, url) {
    window.open(`mailto:?subject=${subject}&body=Schau%20dir%20diese%20Stellenanzeige%20an:%20${url}`, '_blank');
}

function shareViaLinkedIn(url) {
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank');
}

function shareViaXing(url) {
    window.open(`https://www.xing.com/spi/shares/new?url=${url}`, '_blank');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showNotification('Link in Zwischenablage kopiert', 'success');
        const modal = document.querySelector('.share-modal');
        if (modal) {
            document.body.removeChild(modal);
        }
    }).catch(function() {
        showNotification('Fehler beim Kopieren', 'error');
    });
}
</script>

<style>
/* Share Modal und Notification Styles */
.share-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.share-modal-content {
    background: white;
    padding: 2rem;
    border-radius: 0.5rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.share-option {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.share-option:hover {
    background-color: #f9fafb;
}

.share-option svg {
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    color: white;
    font-weight: 500;
    z-index: 1001;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.notification.show {
    transform: translateX(0);
}

.notification.success {
    background-color: #10b981;
}

.notification.error {
    background-color: #ef4444;
}
</style>
@endsection 