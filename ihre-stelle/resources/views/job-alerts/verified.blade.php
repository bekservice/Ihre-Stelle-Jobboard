@extends('layouts.app')

@section('title', 'Job-Alert bestätigt')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-orange-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">Job-Alert bestätigt!</h1>
            
            <p class="text-lg text-gray-600 mb-6">
                Vielen Dank! Ihr Job-Alert für <strong>{{ $alert->email }}</strong> wurde erfolgreich bestätigt.
            </p>

            <!-- Alert Details -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Ihre Job-Alert-Einstellungen:</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-blue-800 font-medium">E-Mail:</span>
                        <span class="text-blue-700">{{ $alert->email }}</span>
                    </div>
                    @if($alert->name)
                    <div class="flex justify-between">
                        <span class="text-blue-800 font-medium">Name:</span>
                        <span class="text-blue-700">{{ $alert->name }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-blue-800 font-medium">Kategorie:</span>
                        <span class="text-blue-700">{{ $alert->category ?: 'Alle Kategorien' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-800 font-medium">Standort:</span>
                        <span class="text-blue-700">{{ $alert->location ?: 'Alle Standorte' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-800 font-medium">Job-Typen:</span>
                        <span class="text-blue-700">
                            @if($alert->job_types && count($alert->job_types) > 0)
                                {{ implode(', ', $alert->job_types) }}
                            @else
                                Alle Job-Typen
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-800 font-medium">Frequenz:</span>
                        <span class="text-blue-700">
                            @switch($alert->frequency)
                                @case('immediate')
                                    Sofort
                                    @break
                                @case('daily')
                                    Täglich
                                    @break
                                @case('weekly')
                                    Wöchentlich
                                    @break
                                @default
                                    {{ $alert->frequency }}
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                <p class="text-sm text-green-800">
                    <strong>Aktiv:</strong> Sie erhalten ab sofort Benachrichtigungen über passende Jobs!
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('jobs.search') }}" class="btn-primary">
                    Jobs durchsuchen
                </a>
                <a href="{{ route('job-alerts.manage', $alert->token) }}" class="btn-outline">
                    Einstellungen verwalten
                </a>
            </div>
        </div>

        <!-- Management Info -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Verwaltung Ihres Job-Alerts</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <p>
                    <strong>Einstellungen ändern:</strong> 
                    <a href="{{ route('job-alerts.manage', $alert->token) }}" class="text-primary-orange hover:underline">
                        Klicken Sie hier
                    </a> 
                    um Ihre Job-Alert-Einstellungen zu bearbeiten.
                </p>
                <p>
                    <strong>Abmelden:</strong> 
                    In jeder Benachrichtigungs-E-Mail finden Sie einen Abmelde-Link, oder Sie können sich 
                    <a href="{{ route('job-alerts.unsubscribe', $alert->token) }}" class="text-primary-orange hover:underline">
                        hier direkt abmelden
                    </a>.
                </p>
                <p>
                    <strong>Bookmark:</strong> 
                    Speichern Sie diese Seite als Lesezeichen, um jederzeit auf Ihre Job-Alert-Verwaltung zugreifen zu können.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 