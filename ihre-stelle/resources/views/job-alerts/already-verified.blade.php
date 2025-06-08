@extends('layouts.app')

@section('title', 'Job-Alert bereits bestätigt')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-orange-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Info Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-6">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">Bereits bestätigt</h1>
            
            <p class="text-lg text-gray-600 mb-6">
                Ihr Job-Alert für <strong>{{ $alert->email }}</strong> wurde bereits am 
                <strong>{{ $alert->email_verified_at->format('d.m.Y') }}</strong> bestätigt.
            </p>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <p class="text-blue-800">
                    <strong>Status:</strong> Ihr Job-Alert ist aktiv und Sie erhalten Benachrichtigungen 
                    über passende Jobs entsprechend Ihrer Einstellungen.
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

        <!-- Quick Info -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ihre aktuellen Einstellungen</h3>
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span class="font-medium">Kategorie:</span>
                    <span>{{ $alert->category ?: 'Alle Kategorien' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Standort:</span>
                    <span>{{ $alert->location ?: 'Alle Standorte' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Frequenz:</span>
                    <span>
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
                <div class="flex justify-between">
                    <span class="font-medium">Status:</span>
                    <span class="{{ $alert->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $alert->is_active ? 'Aktiv' : 'Inaktiv' }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                <a href="{{ route('job-alerts.manage', $alert->token) }}" class="text-primary-orange hover:underline font-medium">
                    → Einstellungen bearbeiten
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 