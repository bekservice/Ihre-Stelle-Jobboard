@extends('layouts.app')

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@push('meta')
    <!-- Open Graph Tags -->
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('cities.show', $city->slug) }}">
    <meta property="og:image" content="{{ asset('logo/ihre-stelle_logo_quer-logo.png') }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ asset('logo/ihre-stelle_logo_quer-logo.png') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('cities.show', $city->slug) }}">
    
    <!-- Structured Data for Place -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Place",
        "name": "{{ $city->city }}",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "{{ $city->city }}",
            @if($city->postal_code)
            "postalCode": "{{ $city->postal_code }}",
            @endif
            "addressCountry": "{{ $city->country ?: 'DE' }}"
        },
        @if($city->latitude && $city->longitude)
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": {{ $city->latitude }},
            "longitude": {{ $city->longitude }}
        },
        @endif
        "containsPlace": [
            @foreach($jobs->take(5) as $job)
            {
                "@type": "JobPosting",
                "title": "{{ $job->title }}",
                "description": "{{ strip_tags($job->description) }}",
                "datePosted": "{{ $job->created_at->format('Y-m-d') }}",
                "hiringOrganization": {
                    "@type": "Organization",
                    "name": "{{ $job->arbeitsgeber_name ?: 'Confidential' }}"
                },
                "jobLocation": {
                    "@type": "Place",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "{{ $city->city }}",
                        "addressCountry": "DE"
                    }
                }
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
    </script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb Navigation -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">Home</a></li>
            <li class="before:content-['/'] before:mx-2">
                <a href="{{ route('cities.index') }}" class="hover:text-blue-600">Städte</a>
            </li>
            <li class="before:content-['/'] before:mx-2 text-gray-900 font-medium">
                {{ $city->city }}
            </li>
        </ol>
    </nav>

    <!-- City Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Jobs in {{ $city->city }}
            </h1>
            <p class="text-xl text-gray-600 mb-6">
                {{ $totalJobs }} {{ $totalJobs == 1 ? 'aktuelle Stellenanzeige' : 'aktuelle Stellenanzeigen' }} verfügbar
            </p>
            
            <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-600">
                @if($city->postal_code)
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    PLZ: {{ $city->postal_code }}
                </span>
                @endif
                
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $topEmployers->count() }} {{ $topEmployers->count() == 1 ? 'Arbeitgeber' : 'Arbeitgeber' }}
                </span>
                
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $topCategories->count() }} {{ $topCategories->count() == 1 ? 'Bereich' : 'Bereiche' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600 mb-2">{{ $totalJobs }}</div>
            <div class="text-gray-700">Offene Stellen</div>
        </div>
        
        <div class="bg-green-50 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-green-600 mb-2">{{ $topEmployers->count() }}</div>
            <div class="text-gray-700">Arbeitgeber</div>
        </div>
        
        <div class="bg-purple-50 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-purple-600 mb-2">{{ $topCategories->count() }}</div>
            <div class="text-gray-700">Branchen</div>
        </div>
        
        <div class="bg-orange-50 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-orange-600 mb-2">{{ number_format($avgJobsPerEmployer, 1) }}</div>
            <div class="text-gray-700">⌀ Jobs/Arbeitgeber</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Current Job Openings -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    Aktuelle Stellenangebote in {{ $city->city }}
                </h2>
                
                @if($jobs->count() > 0)
                <div class="space-y-4">
                    @foreach($jobs as $job)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <a href="{{ route('jobs.show', $job->slug) }}" 
                                   class="hover:text-blue-600 transition-colors">
                                    {{ $job->title }}
                                </a>
                            </h3>
                            @if($job->created_at->isToday())
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                Neu heute
                            </span>
                            @elseif($job->created_at->isYesterday())
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                Gestern
                            </span>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                            @if($job->arbeitsgeber_name)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2v8h12V6H4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $job->arbeitsgeber_name }}
                            </span>
                            @endif
                            
                            @if($job->kategorie)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $job->kategorie }}
                            </span>
                            @endif
                            
                            @if($job->job_type && is_array($job->job_type))
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ implode(', ', $job->job_type) }}
                            </span>
                            @endif
                        </div>
                        
                        @if($job->description)
                        <p class="text-gray-700 mb-4 line-clamp-3">
                            {{ Str::limit(strip_tags($job->description), 200) }}
                        </p>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                Veröffentlicht {{ $job->created_at->diffForHumans() }}
                            </span>
                            <a href="{{ route('jobs.show', $job->slug) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                Details ansehen
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $jobs->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6M8 8v10a2 2 0 002 2h4a2 2 0 002-2V8M8 8V6a2 2 0 012-2h4a2 2 0 012-2V8"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Keine aktuellen Stellenangebote</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        In {{ $city->city }} sind derzeit keine Stellen verfügbar.
                    </p>
                </div>
                @endif
            </section>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Top Employers -->
            @if($topEmployers->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top-Arbeitgeber in {{ $city->city }}</h3>
                <div class="space-y-3">
                    @foreach($topEmployers as $employer)
                    <div class="flex justify-between items-center">
                        <a href="{{ route('employers.show', $employer->slug) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $employer->arbeitsgeber_name }}
                        </a>
                        <span class="text-sm text-gray-500">
                            {{ $employer->job_count }} {{ $employer->job_count == 1 ? 'Job' : 'Jobs' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Top Categories -->
            @if($topCategories->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Beliebte Bereiche</h3>
                <div class="space-y-3">
                    @foreach($topCategories as $category)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">{{ $category->kategorie }}</span>
                        <span class="text-sm text-gray-500">
                            {{ $category->job_count }} {{ $category->job_count == 1 ? 'Job' : 'Jobs' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Employment Types -->
            @if($employmentTypes->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Beschäftigungsarten</h3>
                <div class="space-y-3">
                    @foreach($employmentTypes as $type => $count)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">{{ $type }}</span>
                        <span class="text-sm text-gray-500">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Nearby Cities -->
            @if($nearbyCities->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Jobs in der Nähe</h3>
                <div class="space-y-2">
                    @foreach($nearbyCities as $nearbyCity)
                    <div class="flex justify-between items-center">
                        <a href="{{ route('cities.show', $nearbyCity->slug) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            {{ $nearbyCity->city }}
                        </a>
                        <span class="text-sm text-gray-500">
                            {{ $nearbyCity->job_count }} {{ $nearbyCity->job_count == 1 ? 'Job' : 'Jobs' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- FAQ Section for ChatGPT Optimization -->
    <section class="mt-12 bg-gray-50 rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            Häufig gestellte Fragen zu Jobs in {{ $city->city }}
        </h2>
        
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Wie viele Jobs gibt es aktuell in {{ $city->city }}?
                </h3>
                <p class="text-gray-700">
                    In {{ $city->city }} sind derzeit {{ $totalJobs }} 
                    {{ $totalJobs == 1 ? 'Stellenanzeige' : 'Stellenanzeigen' }} verfügbar.
                    @if($topEmployers->count() > 0)
                        Die meisten Jobs bieten {{ $topEmployers->first()->arbeitsgeber_name }} 
                        ({{ $topEmployers->first()->job_count }} Stellen).
                    @endif
                </p>
            </div>
            
            @if($topCategories->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Welche Branchen sind in {{ $city->city }} besonders gefragt?
                </h3>
                <p class="text-gray-700">
                    Die gefragtesten Bereiche in {{ $city->city }} sind: 
                    {{ $topCategories->pluck('kategorie')->implode(', ') }}.
                    @if($topCategories->first())
                        Der Bereich "{{ $topCategories->first()->kategorie }}" hat mit 
                        {{ $topCategories->first()->job_count }} Stellen die meisten Angebote.
                    @endif
                </p>
            </div>
            @endif
            
            @if($employmentTypes->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Welche Beschäftigungsarten werden in {{ $city->city }} angeboten?
                </h3>
                <p class="text-gray-700">
                    In {{ $city->city }} werden verschiedene Beschäftigungsarten angeboten: 
                    {{ $employmentTypes->keys()->implode(', ') }}.
                    @if($employmentTypes->first())
                        Am häufigsten sind {{ $employmentTypes->keys()->first() }}-Stellen 
                        ({{ $employmentTypes->first() }} Angebote).
                    @endif
                </p>
            </div>
            @endif
            
            @if($nearbyCities->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Gibt es auch Jobs in der Nähe von {{ $city->city }}?
                </h3>
                <p class="text-gray-700">
                    Ja, in der Umgebung von {{ $city->city }} gibt es weitere Stellenangebote in: 
                    {{ $nearbyCities->pluck('city')->implode(', ') }}.
                    Insgesamt sind das {{ $nearbyCities->sum('job_count') }} zusätzliche Jobs.
                </p>
            </div>
            @endif
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Wie kann ich mich auf Jobs in {{ $city->city }} bewerben?
                </h3>
                <p class="text-gray-700">
                    Sie können sich direkt über unsere Plattform auf alle Stellenangebote in {{ $city->city }} bewerben. 
                    Klicken Sie einfach auf "Details ansehen" bei der gewünschten Stelle und folgen Sie den Bewerbungsanweisungen. 
                    Viele Arbeitgeber ermöglichen auch eine direkte Online-Bewerbung.
                </p>
            </div>
        </div>
    </section>
</div>
@endsection 