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
                        @if($job->info_fuer_uns && Storage::disk('public')->exists($job->info_fuer_uns))
                            <img src="{{ asset('storage/' . $job->info_fuer_uns) }}" 
                                 alt="Logo von {{ $job->arbeitsgeber_name ?? 'Unternehmen' }}" 
                                 class="w-16 h-16 rounded-lg object-cover ml-6">
                        @elseif($job->job_logo && is_array($job->job_logo) && count($job->job_logo) > 0)
                            <img src="{{ $job->job_logo[0]['url'] ?? '' }}" 
                                 alt="Logo von {{ $job->arbeitsgeber_name ?? 'Unternehmen' }}" 
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
                        <button id="save-job-btn" class="btn-outline px-6 py-3 rounded-lg font-semibold" data-job-id="{{ $job->id }}" data-job-title="{{ $job->title }}">
                            <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            <span id="save-job-text">Job speichern</span>
                        </button>
                        <button id="share-job-btn" class="btn-outline px-6 py-3 rounded-lg font-semibold">
                            <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
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

                    {{-- Bewerbungsfrist versteckt --}}
                    {{-- @if($job->ablaufdatum)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-1">Bewerbungsfrist</h4>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($job->ablaufdatum)->format('d.m.Y') }}</p>
                        </div>
                    @endif --}}
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
                        <button id="save-job-sidebar-btn" class="btn-outline block w-full text-center px-6 py-3 rounded-lg font-semibold" data-job-id="{{ $job->id }}" data-job-title="{{ $job->title }}">
                            <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            <span id="save-job-sidebar-text">Job speichern</span>
                        </button>
                        <button id="share-job-sidebar-btn" class="btn-outline block w-full text-center px-6 py-3 rounded-lg font-semibold">
                            <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            Teilen
                        </button>
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

/* Share Modal Styles */
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Job speichern Funktionalität
    const saveButtons = document.querySelectorAll('#save-job-btn, #save-job-sidebar-btn');
    const saveTexts = document.querySelectorAll('#save-job-text, #save-job-sidebar-text');
    
    saveButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            const jobId = this.dataset.jobId;
            const jobTitle = this.dataset.jobTitle;
            
            // Lokale Speicherung verwenden
            let savedJobs = JSON.parse(localStorage.getItem('savedJobs') || '[]');
            
            if (savedJobs.includes(jobId)) {
                // Job entfernen
                savedJobs = savedJobs.filter(id => id !== jobId);
                localStorage.setItem('savedJobs', JSON.stringify(savedJobs));
                
                saveTexts.forEach(text => {
                    text.textContent = 'Job speichern';
                });
                
                showNotification('Job aus gespeicherten Jobs entfernt', 'success');
            } else {
                // Job hinzufügen
                savedJobs.push(jobId);
                localStorage.setItem('savedJobs', JSON.stringify(savedJobs));
                
                saveTexts.forEach(text => {
                    text.textContent = 'Gespeichert ✓';
                });
                
                showNotification('Job erfolgreich gespeichert', 'success');
            }
        });
    });
    
    // Teilen Funktionalität
    const shareButtons = document.querySelectorAll('#share-job-btn, #share-job-sidebar-btn');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function() {
            showShareModal();
        });
    });
    
    // Prüfen ob Job bereits gespeichert ist
    function checkIfJobSaved() {
        const jobId = document.querySelector('#save-job-btn')?.dataset.jobId;
        if (jobId) {
            const savedJobs = JSON.parse(localStorage.getItem('savedJobs') || '[]');
            if (savedJobs.includes(jobId)) {
                saveTexts.forEach(text => {
                    text.textContent = 'Gespeichert ✓';
                });
            }
        }
    }
    
    // Share Modal anzeigen
    function showShareModal() {
        const currentUrl = window.location.href;
        const jobTitle = '{{ addslashes($job->title) }}';
        const companyName = '{{ addslashes($job->arbeitsgeber_name ?? "") }}';
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
                    <div class="share-option" onclick="shareViaWhatsApp('${encodeURIComponent(shareText)}', '${encodeURIComponent(currentUrl)}')">
                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.63"/>
                        </svg>
                        <span>WhatsApp</span>
                    </div>
                    
                    <div class="share-option" onclick="shareViaEmail('${encodeURIComponent(shareText)}', '${encodeURIComponent(currentUrl)}')">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>E-Mail</span>
                    </div>
                    
                    <div class="share-option" onclick="shareViaLinkedIn('${encodeURIComponent(currentUrl)}')">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        <span>LinkedIn</span>
                    </div>
                    
                    <div class="share-option" onclick="shareViaXing('${encodeURIComponent(currentUrl)}')">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.188 0c-.517 0-.741.325-.927.66 0 0-7.455 13.224-7.702 13.657.284.52 4.56 8.668 4.56 8.668.19.34.411.66.927.66h3.905c.262 0 .442-.043.442-.252 0-.113-.07-.252-.442-.252 0 0-4.572-8.395-4.572-8.395s7.49-13.074 7.49-13.074c.367-.619.367-.619.367-.732 0-.21-.18-.252-.442-.252h-3.906zm-11.442 4.185c-.262 0-.44.04-.44.252 0 .113.178.252.44.252h2.861s-1.584 2.827-1.584 2.827c-.184.33-.184.66 0 .99 0 0 2.904 5.357 2.904 5.357s-3.67 6.544-3.67 6.544c-.190.34-.190.68 0 1.02.19.34.41.66.927.66h3.905c.262 0 .44-.043.44-.252 0-.113-.178-.252-.44-.252h-2.861s3.67-6.544 3.67-6.544c.184-.33.184-.66 0-.99 0 0-2.904-5.357-2.904-5.357s1.584-2.827 1.584-2.827c.190-.34.19-.68 0-1.02-.19-.34-.41-.66-.927-.66h-3.905z"/>
                        </svg>
                        <span>Xing</span>
                    </div>
                    
                    <div class="share-option" onclick="copyToClipboard('${currentUrl}')">
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
    
    // Notification anzeigen
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        // Automatisch entfernen
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Beim Laden prüfen ob Job gespeichert ist
    checkIfJobSaved();
});

// Share Funktionen
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
        // Modal schließen
        const modal = document.querySelector('.share-modal');
        if (modal) {
            document.body.removeChild(modal);
        }
    }).catch(function() {
        showNotification('Fehler beim Kopieren', 'error');
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Automatisch entfernen
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
@endsection
