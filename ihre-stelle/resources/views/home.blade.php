@extends('layouts.app')

@section('title', 'Ihre Stelle - Jobs finden leicht gemacht')
@section('description', 'Finden Sie Ihren Traumjob bei Ihre Stelle. Über ' . number_format($totalJobs) . ' aktuelle Stellenanzeigen aus allen Branchen warten auf Sie.')

@section('content')
<!-- Hero Section -->
<div class="hero-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center fade-in">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 text-primary-blue">
                Finden Sie Ihren
                <span class="text-primary-orange">Traumjob</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-600 max-w-3xl mx-auto">
                Über {{ number_format($totalJobs) }} aktuelle Stellenanzeigen aus allen Branchen. 
                Starten Sie jetzt Ihre Karriere!
            </p>
            
            <!-- Search Form -->
            <div class="max-w-4xl mx-auto slide-up">
                <form action="{{ route('jobs.search') }}" method="GET" id="home-search-form" class="search-container">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="q" class="form-label">Was suchen Sie?</label>
                            <input type="text" 
                                   id="q" 
                                   name="q" 
                                   placeholder="z.B. Marketing Manager, Entwickler..."
                                   class="search-input w-full">
                        </div>
                        
                        <div>
                            <label for="location" class="form-label">Wo?</label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   placeholder="Stadt, PLZ oder Adresse..."
                                   class="search-input w-full"
                                   autocomplete="off">
                            <div id="location-suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                        </div>
                        
                        <div>
                            <label for="category" class="form-label">Kategorie</label>
                            <select name="category" id="category" class="search-input w-full">
                                <option value="">Alle Kategorien</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->kategorie }}">{{ $category->kategorie }} ({{ $category->count }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="search-button w-full">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Jobs finden
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6 card-hover">
                <div class="text-4xl font-bold text-primary-orange mb-2">{{ number_format($totalJobs) }}+</div>
                <div class="text-gray-600 text-lg">Aktuelle Jobs</div>
            </div>
            <div class="p-6 card-hover">
                <div class="text-4xl font-bold text-primary-orange mb-2">{{ $categories->count() }}+</div>
                <div class="text-gray-600 text-lg">Branchen</div>
            </div>
            <div class="p-6 card-hover">
                <div class="text-4xl font-bold text-primary-orange mb-2">{{ $locations->count() }}+</div>
                <div class="text-gray-600 text-lg">Standorte</div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Jobs Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">Aktuelle Top-Jobs</h2>
            <p class="text-xl text-gray-600">Entdecken Sie die neuesten Stellenangebote</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($featuredJobs as $job)
                <div class="job-card p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-primary-blue mb-2 line-clamp-2">
                                <a href="{{ route('jobs.show', $job) }}" class="hover:text-primary-orange transition-colors">
                                    {{ $job->title }}
                                </a>
                            </h3>
                            @if($job->arbeitsgeber_name)
                                <p class="text-gray-600 font-medium mb-2">{{ $job->arbeitsgeber_name }}</p>
                            @endif
                        </div>
                        @if($job->job_logo && is_array($job->job_logo) && count($job->job_logo) > 0)
                            <img src="{{ $job->job_logo[0]['url'] ?? '' }}" 
                                 alt="Logo" 
                                 class="w-12 h-12 rounded-lg object-cover ml-4">
                        @endif
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        @if($job->city)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $job->city }}
                        @endif
                        
                        @if($job->job_type && is_array($job->job_type))
                            <span class="mx-2">•</span>
                            <span>{{ implode(', ', $job->job_type) }}</span>
                        @endif
                    </div>
                    
                    @if($job->kategorie)
                        <div class="mb-4">
                            <span class="job-badge">
                                {{ $job->kategorie }}
                            </span>
                        </div>
                    @endif
                    
                    @if($job->description)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ Str::limit(strip_tags($job->formatted_description), 120) }}
                        </p>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            {{ $job->created_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('jobs.show', $job) }}" 
                           class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                            Details ansehen
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center">
            <a href="{{ route('jobs.search') }}" 
               class="btn-primary inline-block px-8 py-3 rounded-lg text-lg font-semibold">
                Alle Jobs ansehen
            </a>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">Jobs nach Kategorien</h2>
            <p class="text-xl text-gray-600">Finden Sie Jobs in Ihrer Branche</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('jobs.search', ['category' => $category->kategorie]) }}" 
                   class="card-gradient card-hover rounded-lg p-6 text-center group">
                    <h3 class="font-semibold text-primary-blue group-hover:text-primary-orange mb-2">
                        {{ $category->kategorie }}
                    </h3>
                    <p class="text-sm text-gray-500">{{ $category->count }} Jobs</p>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="cta-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Bereit für den nächsten Karriereschritt?</h2>
        <p class="text-xl text-gray-100 mb-8 max-w-2xl mx-auto">
            Melden Sie sich für Job-Alerts an und verpassen Sie nie wieder Ihren Traumjob.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('jobs.search') }}" 
               class="btn-secondary px-8 py-3 rounded-lg text-lg font-semibold">
                Jobs durchsuchen
            </a>
            <a href="#" 
               class="btn-outline-white px-8 py-3 rounded-lg text-lg font-semibold">
                Job-Alert erstellen
            </a>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Position relative for location input container */
#home-search-form .grid > div:nth-child(2) {
    position: relative;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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