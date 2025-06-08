@extends('layouts.app')

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@push('meta')
    <!-- Open Graph Tags -->
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('employers.show', $employer->slug) }}">
    @if($logoUrl)
    <meta property="og:image" content="{{ $logoUrl }}">
    @endif
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if($logoUrl)
    <meta name="twitter:image" content="{{ $logoUrl }}">
    @endif
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('employers.show', $employer->slug) }}">
    
    <!-- Structured Data for Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ $employer->arbeitsgeber_name }}",
        "url": "{{ $websiteUrl ?: route('employers.show', $employer->slug) }}",
        @if($logoUrl)
        "logo": "{{ $logoUrl }}",
        @endif
        @if($phoneNumber)
        "telephone": "{{ $phoneNumber }}",
        @endif
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "{{ $employer->primary_city }}",
            "addressCountry": "DE"
        },
        "jobPosting": [
            @foreach($jobs->take(5) as $job)
            {
                "@type": "JobPosting",
                "title": "{{ $job->title }}",
                "description": "{{ strip_tags($job->description) }}",
                "datePosted": "{{ $job->created_at->format('Y-m-d') }}",
                "hiringOrganization": {
                    "@type": "Organization",
                    "name": "{{ $employer->arbeitsgeber_name }}"
                },
                "jobLocation": {
                    "@type": "Place",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "{{ $job->city }}",
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
                <a href="{{ route('employers.index') }}" class="hover:text-blue-600">Arbeitgeber</a>
            </li>
            <li class="before:content-['/'] before:mx-2 text-gray-900 font-medium">
                {{ $employer->arbeitsgeber_name }}
            </li>
        </ol>
    </nav>

    <!-- Company Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            @if($logoUrl)
            <div class="flex-shrink-0">
                <img src="{{ $logoUrl }}" alt="{{ $employer->arbeitsgeber_name }} Logo" 
                     class="w-24 h-24 object-contain rounded-lg border border-gray-200">
            </div>
            @endif
            
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Jobs bei {{ $employer->arbeitsgeber_name }}
                </h1>
                <p class="text-xl text-gray-600 mb-4">
                    {{ $employer->job_count }} {{ $employer->job_count == 1 ? 'offene Stelle' : 'offene Stellen' }} verfügbar
                </p>
                
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                    @if($cities->count() > 0)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        Standorte: {{ $cities->pluck('city')->take(3)->implode(', ') }}
                        @if($cities->count() > 3)
                            und {{ $cities->count() - 3 }} weitere
                        @endif
                    </div>
                    @endif
                    
                    @if($websiteUrl)
                    <a href="{{ $websiteUrl }}" target="_blank" rel="noopener" 
                       class="flex items-center text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                        </svg>
                        Website besuchen
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600 mb-2">{{ $employer->job_count }}</div>
            <div class="text-gray-700">Offene Stellen</div>
        </div>
        
        <div class="bg-green-50 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-green-600 mb-2">{{ $cities->count() }}</div>
            <div class="text-gray-700">{{ $cities->count() == 1 ? 'Standort' : 'Standorte' }}</div>
        </div>
        
        <div class="bg-purple-50 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-purple-600 mb-2">{{ $categories->count() }}</div>
            <div class="text-gray-700">{{ $categories->count() == 1 ? 'Bereich' : 'Bereiche' }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Current Job Openings -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    Aktuelle Stellenangebote bei {{ $employer->arbeitsgeber_name }}
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
                            @if($job->city)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $job->city }}
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
                        {{ $employer->arbeitsgeber_name }} hat derzeit keine offenen Stellen.
                    </p>
                </div>
                @endif
            </section>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Company Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Über {{ $employer->arbeitsgeber_name }}</h3>
                
                @if($websiteUrl)
                <div class="mb-3">
                    <a href="{{ $websiteUrl }}" target="_blank" rel="noopener" 
                       class="text-blue-600 hover:text-blue-800 break-all">
                        {{ str_replace(['https://', 'http://'], '', $websiteUrl) }}
                    </a>
                </div>
                @endif
                
                @if($phoneNumber)
                <div class="mb-3">
                    <span class="text-gray-600">Telefon: </span>
                    <a href="tel:{{ $phoneNumber }}" class="text-blue-600 hover:text-blue-800">
                        {{ $phoneNumber }}
                    </a>
                </div>
                @endif
            </div>

            <!-- Locations -->
            @if($cities->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Standorte</h3>
                <div class="space-y-2">
                    @foreach($cities as $city)
                    <div class="flex justify-between items-center">
                        <a href="{{ route('cities.show', $city->slug) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            {{ $city->city }}
                        </a>
                        <span class="text-sm text-gray-500">
                            {{ $city->job_count }} {{ $city->job_count == 1 ? 'Job' : 'Jobs' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Categories -->
            @if($categories->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bereiche</h3>
                <div class="space-y-2">
                    @foreach($categories as $category)
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
        </div>
    </div>

    <!-- FAQ Section for ChatGPT Optimization -->
    <section class="mt-12 bg-gray-50 rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            Häufig gestellte Fragen zu Jobs bei {{ $employer->arbeitsgeber_name }}
        </h2>
        
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Wie viele offene Stellen hat {{ $employer->arbeitsgeber_name }} aktuell?
                </h3>
                <p class="text-gray-700">
                    {{ $employer->arbeitsgeber_name }} hat derzeit {{ $employer->job_count }} 
                    {{ $employer->job_count == 1 ? 'offene Stelle' : 'offene Stellen' }} 
                    @if($cities->count() > 0)
                        in {{ $cities->count() }} {{ $cities->count() == 1 ? 'Standort' : 'Standorten' }}
                    @endif
                    verfügbar.
                </p>
            </div>
            
            @if($cities->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    In welchen Städten bietet {{ $employer->arbeitsgeber_name }} Jobs an?
                </h3>
                <p class="text-gray-700">
                    {{ $employer->arbeitsgeber_name }} hat Stellenangebote in folgenden Städten: 
                    {{ $cities->pluck('city')->implode(', ') }}.
                </p>
            </div>
            @endif
            
            @if($categories->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    In welchen Bereichen stellt {{ $employer->arbeitsgeber_name }} ein?
                </h3>
                <p class="text-gray-700">
                    {{ $employer->arbeitsgeber_name }} sucht Mitarbeiter in folgenden Bereichen: 
                    {{ $categories->pluck('kategorie')->implode(', ') }}.
                </p>
            </div>
            @endif
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Wie kann ich mich bei {{ $employer->arbeitsgeber_name }} bewerben?
                </h3>
                <p class="text-gray-700">
                    Sie können sich direkt über unsere Plattform auf die Stellenangebote von {{ $employer->arbeitsgeber_name }} bewerben. 
                    Klicken Sie einfach auf "Details ansehen" bei der gewünschten Stelle und folgen Sie den Bewerbungsanweisungen.
                </p>
            </div>
        </div>
    </section>
</div>
@endsection 