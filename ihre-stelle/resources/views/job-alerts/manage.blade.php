@extends('layouts.app')

@section('title', 'Job-Alert verwalten')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-orange-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Job-Alert verwalten</h1>
            <p class="text-lg text-gray-600">
                Bearbeiten Sie Ihre Job-Alert-Einstellungen oder melden Sie sich ab.
            </p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Formular -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('job-alerts.update', $alert->token) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Status -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900">Status</h3>
                            <p class="text-sm text-blue-700">
                                E-Mail: {{ $alert->email }}
                                @if($alert->email_verified_at)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                        Verifiziert
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                        Nicht verifiziert
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ $alert->is_active ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-orange focus:ring-primary-orange">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Aktiv</label>
                        </div>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Name (optional)
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $alert->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-orange focus:border-transparent transition-colors"
                           placeholder="Ihr Name">
                </div>

                <!-- Kategorie -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategorie
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-orange focus:border-transparent transition-colors">
                        <option value="">Alle Kategorien</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category', $alert->category) == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Standort -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Standort
                    </label>
                    <select id="location" 
                            name="location" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-orange focus:border-transparent transition-colors">
                        <option value="">Alle Standorte</option>
                        @foreach($locations as $location)
                            <option value="{{ $location }}" {{ old('location', $alert->location) == $location ? 'selected' : '' }}>
                                {{ $location }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Job-Typen -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Job-Typen
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($allJobTypes as $jobType)
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="job_types[]" 
                                       value="{{ $jobType }}"
                                       {{ in_array($jobType, old('job_types', $alert->job_types ?: [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-orange focus:ring-primary-orange">
                                <span class="ml-2 text-sm text-gray-700">{{ $jobType }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Lassen Sie alle Felder leer, um über alle Job-Typen benachrichtigt zu werden.
                    </p>
                </div>

                <!-- Benachrichtigungsfrequenz -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Benachrichtigungsfrequenz *
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="frequency" 
                                   value="immediate"
                                   {{ old('frequency', $alert->frequency) == 'immediate' ? 'checked' : '' }}
                                   class="text-primary-orange focus:ring-primary-orange">
                            <span class="ml-2 text-sm text-gray-700">
                                <strong>Sofort</strong> - Bei jedem neuen Job
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="frequency" 
                                   value="daily"
                                   {{ old('frequency', $alert->frequency) == 'daily' ? 'checked' : '' }}
                                   class="text-primary-orange focus:ring-primary-orange">
                            <span class="ml-2 text-sm text-gray-700">
                                <strong>Täglich</strong> - Zusammenfassung einmal am Tag
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="frequency" 
                                   value="weekly"
                                   {{ old('frequency', $alert->frequency) == 'weekly' ? 'checked' : '' }}
                                   class="text-primary-orange focus:ring-primary-orange">
                            <span class="ml-2 text-sm text-gray-700">
                                <strong>Wöchentlich</strong> - Zusammenfassung einmal pro Woche
                            </span>
                        </label>
                    </div>
                    @error('frequency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fehler-Anzeige -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <ul class="text-sm text-red-600 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="btn-primary px-8 py-3 text-lg font-semibold">
                        Einstellungen speichern
                    </button>
                    <a href="{{ route('job-alerts.unsubscribe', $alert->token) }}" 
                       class="btn-outline-white border-red-300 text-red-600 hover:bg-red-50"
                       onclick="return confirm('Sind Sie sicher, dass Sie sich abmelden möchten?')">
                        Abmelden
                    </a>
                </div>
            </form>
        </div>

        <!-- Info -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informationen</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span class="font-medium">Erstellt am:</span>
                    <span>{{ $alert->created_at->format('d.m.Y H:i') }}</span>
                </div>
                @if($alert->last_sent_at)
                <div class="flex justify-between">
                    <span class="font-medium">Letzte Benachrichtigung:</span>
                    <span>{{ $alert->last_sent_at->format('d.m.Y H:i') }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="font-medium">Status:</span>
                    <span class="{{ $alert->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $alert->is_active ? 'Aktiv' : 'Inaktiv' }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    <strong>Bookmark-Tipp:</strong> Speichern Sie diese Seite als Lesezeichen, 
                    um jederzeit auf Ihre Job-Alert-Einstellungen zugreifen zu können.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 