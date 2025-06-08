@extends('layouts.app')

@section('title', $job->title . ' - ' . ($job->arbeitsgeber_name ?? 'Ihre Stelle'))
@section('description', Str::limit(strip_tags($job->formatted_description), 160))
@section('keywords', $job->title . ', ' . ($job->kategorie ?? '') . ', ' . ($job->city ?? '') . ', Jobs, Stellenanzeigen, Karriere, Bewerbung')

@push('head')
    @include('components.job-structured-data', ['job' => $job])
@endpush

@section('content')
<div class="bg-white">
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a>
                </li>
                <li>
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li>
                    <a href="{{ route('jobs.search') }}" class="text-gray-500 hover:text-gray-700">Jobs</a>
                </li>
                <li>
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">{{ Str::limit($job->title, 50) }}</li>
            </ol>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Job Header -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $job->title }}</h1>
                            @if($job->arbeitsgeber_name)
                                <p class="text-xl text-gray-700 font-medium mb-2">{{ $job->arbeitsgeber_name }}</p>
                            @endif
                        </div>
                        @if($job->job_logo && is_array($job->job_logo) && count($job->job_logo) > 0)
                            <img src="{{ $job->job_logo[0]['url'] ?? '' }}" 
                                 alt="Firmenlogo" 
                                 class="w-16 h-16 rounded-lg object-cover ml-6">
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                        @if($job->city)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $job->city }}@if($job->postal_code), {{ $job->postal_code }}@endif
                            </div>
                        @endif
                        
                        @if($job->job_type && is_array($job->job_type))
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ implode(', ', $job->job_type) }}
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

                    @if($job->kategorie)
                        <div class="mb-4">
                            <span class="job-badge text-sm px-3 py-1">
                                {{ $job->kategorie }}
                            </span>
                        </div>
                    @endif

                    <!-- Apply Button -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('application.show', $job) }}" 
                           class="btn-primary px-6 py-3 rounded-lg font-semibold text-center">
                            Express-Bewerbung
                        </a>
                        @if($job->bewerbung_an_mail)
                            <a href="mailto:{{ $job->bewerbung_an_mail }}?subject=Bewerbung: {{ $job->title }}" 
                               class="btn-outline px-6 py-3 rounded-lg font-semibold text-center">
                                Per E-Mail bewerben
                            </a>
                        @endif
                        <button class="btn-outline px-6 py-3 rounded-lg font-semibold">
                            Job speichern
                        </button>
                        <button class="btn-outline px-6 py-3 rounded-lg font-semibold">
                            Teilen
                        </button>
                    </div>
                </div>

                <!-- Job Description -->
                @if($job->description)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Stellenbeschreibung</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! $job->formatted_description !!}
                        </div>
                    </div>
                @endif

                <!-- Benefits -->
                @if($job->benefits && is_array($job->benefits) && count($job->benefits) > 0)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Benefits</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($job->benefits as $benefit)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $benefit }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Related Jobs -->
                @if($relatedJobs->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Ähnliche Jobs</h2>
                        <div class="space-y-4">
                            @foreach($relatedJobs as $relatedJob)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <h3 class="font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('jobs.show', $relatedJob) }}" class="hover:text-blue-600">
                                            {{ $relatedJob->title }}
                                        </a>
                                    </h3>
                                    @if($relatedJob->arbeitsgeber_name)
                                        <p class="text-gray-600 mb-2">{{ $relatedJob->arbeitsgeber_name }}</p>
                                    @endif
                                    <div class="flex items-center text-sm text-gray-500">
                                        @if($relatedJob->city)
                                            <span>{{ $relatedJob->city }}</span>
                                        @endif
                                        @if($relatedJob->job_type && is_array($relatedJob->job_type))
                                            <span class="mx-2">•</span>
                                            <span>{{ implode(', ', $relatedJob->job_type) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Company Info -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Über das Unternehmen</h3>
                    
                    @if($job->arbeitsgeber_name)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-1">Unternehmen</h4>
                            <p class="text-gray-600">{{ $job->arbeitsgeber_name }}</p>
                        </div>
                    @endif

                    @if($job->arbeitsgeber_tel && is_array($job->arbeitsgeber_tel) && count($job->arbeitsgeber_tel) > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-1">Telefon</h4>
                            <p class="text-gray-600">{{ $job->arbeitsgeber_tel[0] }}</p>
                        </div>
                    @endif

                    @if($job->arbeitsgeber_website && is_array($job->arbeitsgeber_website) && count($job->arbeitsgeber_website) > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-1">Website</h4>
                            <a href="{{ $job->arbeitsgeber_website[0] }}" 
                               target="_blank" 
                               class="text-blue-600 hover:text-blue-800 break-all">
                                {{ $job->arbeitsgeber_website[0] }}
                            </a>
                        </div>
                    @endif

                    @if($job->ansprechpartner_hr)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-1">Ansprechpartner</h4>
                            <p class="text-gray-600">{{ $job->ansprechpartner_hr }}</p>
                        </div>
                    @endif
                </div>

                <!-- Job Details -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Job Details</h3>
                    


                    @if($job->schulabschluss)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-1">Schulabschluss</h4>
                            <p class="text-gray-600">{{ $job->schulabschluss }}</p>
                        </div>
                    @endif

                    @if($job->rolle_im_job && is_array($job->rolle_im_job) && count($job->rolle_im_job) > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-1">Rolle</h4>
                            <p class="text-gray-600">{{ implode(', ', $job->rolle_im_job) }}</p>
                        </div>
                    @endif

                    @if($job->ablaufdatum)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-1">Bewerbungsfrist</h4>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($job->ablaufdatum)->format('d.m.Y') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Location Map -->
                @if($job->longitude && $job->latitude)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Standort</h3>
                        <x-job-map :jobs="collect([$job])" height="250px" />
                    </div>
                @endif

                <!-- Apply Box -->
                <div class="card-gradient border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-primary-blue mb-4">Jetzt bewerben</h3>
                    <p class="text-gray-600 mb-4">
                        Nutzen Sie unser Express-Bewerbungsformular für eine schnelle und einfache Bewerbung.
                    </p>
                    <div class="space-y-3">
                        <a href="{{ route('application.show', $job) }}" 
                           class="btn-primary block w-full text-center px-6 py-3 rounded-lg font-semibold">
                            Express-Bewerbung
                        </a>
                        @if($job->bewerbung_an_mail)
                            <a href="mailto:{{ $job->bewerbung_an_mail }}?subject=Bewerbung: {{ $job->title }}" 
                               class="btn-outline block w-full text-center px-6 py-3 rounded-lg font-semibold">
                                Per E-Mail bewerben
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.prose {
    line-height: 1.6;
}

.prose p {
    margin-bottom: 1rem;
}

.prose ul {
    list-style-type: disc;
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose strong {
    font-weight: 600;
    color: #1f2937;
}

.prose h3, .prose h4 {
    font-weight: 600;
    color: #1f2937;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}
</style>
@endsection
