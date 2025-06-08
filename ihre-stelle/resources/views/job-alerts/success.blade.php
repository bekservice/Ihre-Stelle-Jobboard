@extends('layouts.app')

@section('title', 'Job-Alert erstellt')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-orange-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">Job-Alert erfolgreich erstellt!</h1>
            
            <p class="text-lg text-gray-600 mb-6">
                Vielen Dank! Ihr Job-Alert wurde erfolgreich erstellt.
            </p>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Was passiert als nächstes?</h3>
                <div class="space-y-3 text-left">
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-primary-orange text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-0.5">1</span>
                        <p class="text-blue-800">
                            <strong>E-Mail prüfen:</strong> Sie erhalten in wenigen Minuten eine Bestätigungs-E-Mail.
                        </p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-primary-orange text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-0.5">2</span>
                        <p class="text-blue-800">
                            <strong>Bestätigen:</strong> Klicken Sie auf den Bestätigungslink in der E-Mail.
                        </p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-primary-orange text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-0.5">3</span>
                        <p class="text-blue-800">
                            <strong>Benachrichtigungen erhalten:</strong> Ab sofort werden Sie über passende Jobs informiert.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                <p class="text-sm text-yellow-800">
                    <strong>Hinweis:</strong> Prüfen Sie auch Ihren Spam-Ordner, falls Sie keine E-Mail erhalten.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('jobs.search') }}" class="btn-primary">
                    Jobs durchsuchen
                </a>
                <a href="{{ route('job-alerts.create') }}" class="btn-outline">
                    Weiteren Alert erstellen
                </a>
            </div>
        </div>

        <!-- Zusätzliche Informationen -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Häufige Fragen</h3>
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-gray-900">Wie oft erhalte ich Benachrichtigungen?</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        Je nach Ihrer gewählten Einstellung erhalten Sie sofortige Benachrichtigungen, 
                        tägliche oder wöchentliche Zusammenfassungen.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Kann ich meine Einstellungen ändern?</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        Ja, in jeder E-Mail finden Sie einen Link zur Verwaltung Ihrer Job-Alert-Einstellungen.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Wie kann ich mich abmelden?</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        In jeder Benachrichtigungs-E-Mail finden Sie einen Abmelde-Link. 
                        Alternativ können Sie uns auch direkt kontaktieren.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 