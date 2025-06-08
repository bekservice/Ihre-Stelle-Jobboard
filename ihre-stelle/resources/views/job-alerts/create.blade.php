@extends('layouts.app')

@section('title', 'Job-Alert erstellen')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-orange-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Job-Alert erstellen</h1>
            <p class="text-lg text-gray-600">
                Lassen Sie sich über neue Jobs benachrichtigen, die zu Ihren Kriterien passen.
            </p>
        </div>

        <!-- Formular -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('job-alerts.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- E-Mail -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-Mail-Adresse *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-orange focus:border-transparent transition-colors @error('email') border-red-500 @enderror"
                           placeholder="ihre.email@beispiel.de">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name (optional) -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Name (optional)
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
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
                            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
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
                            <option value="{{ $location }}" {{ old('location') == $location ? 'selected' : '' }}>
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
                                       {{ in_array($jobType, old('job_types', [])) ? 'checked' : '' }}
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
                                   {{ old('frequency') == 'immediate' ? 'checked' : '' }}
                                   class="text-primary-orange focus:ring-primary-orange">
                            <span class="ml-2 text-sm text-gray-700">
                                <strong>Sofort</strong> - Bei jedem neuen Job
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="frequency" 
                                   value="daily"
                                   {{ old('frequency', 'daily') == 'daily' ? 'checked' : '' }}
                                   class="text-primary-orange focus:ring-primary-orange">
                            <span class="ml-2 text-sm text-gray-700">
                                <strong>Täglich</strong> - Zusammenfassung einmal am Tag
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="frequency" 
                                   value="weekly"
                                   {{ old('frequency') == 'weekly' ? 'checked' : '' }}
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

                <!-- Datenschutz-Hinweis -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">
                        <strong>Datenschutz:</strong> Ihre E-Mail-Adresse wird nur für die Job-Benachrichtigungen verwendet. 
                        Sie können sich jederzeit abmelden. Weitere Informationen finden Sie in unserer 
                        <a href="{{ route('datenschutz') }}" class="text-primary-orange hover:underline">Datenschutzerklärung</a>.
                    </p>
                </div>

                <!-- Fehler-Anzeige -->
                @if($errors->has('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-red-600">{{ $errors->first('error') }}</p>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" 
                            class="btn-primary px-8 py-3 text-lg font-semibold">
                        Job-Alert erstellen
                    </button>
                </div>
            </form>
        </div>

        <!-- Info-Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">So funktioniert's:</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                    <span class="text-primary-orange mr-2">1.</span>
                    Füllen Sie das Formular mit Ihren gewünschten Job-Kriterien aus
                </li>
                <li class="flex items-start">
                    <span class="text-primary-orange mr-2">2.</span>
                    Sie erhalten eine Bestätigungs-E-Mail zur Verifizierung
                </li>
                <li class="flex items-start">
                    <span class="text-primary-orange mr-2">3.</span>
                    Ab sofort werden Sie über passende Jobs benachrichtigt
                </li>
                <li class="flex items-start">
                    <span class="text-primary-orange mr-2">4.</span>
                    Sie können Ihre Einstellungen jederzeit ändern oder sich abmelden
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection 