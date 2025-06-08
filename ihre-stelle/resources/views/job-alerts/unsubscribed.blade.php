@extends('layouts.app')

@section('title', 'Job-Alert abgemeldet')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-orange-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Info Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-6">
                <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">Job-Alert abgemeldet</h1>
            
            <p class="text-lg text-gray-600 mb-6">
                Ihr Job-Alert für <strong>{{ $alert->email }}</strong> wurde erfolgreich deaktiviert.
            </p>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <p class="text-yellow-800">
                    Sie erhalten ab sofort keine weiteren Job-Benachrichtigungen mehr.
                </p>
            </div>

            <!-- Feedback -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Schade, dass Sie gehen!</h3>
                <p class="text-blue-800 mb-4">
                    Wir würden uns freuen, wenn Sie uns kurz mitteilen, warum Sie sich abgemeldet haben:
                </p>
                <ul class="space-y-2 text-sm text-blue-700">
                    <li>• Zu viele E-Mails erhalten</li>
                    <li>• Jobs entsprachen nicht meinen Erwartungen</li>
                    <li>• Habe bereits einen Job gefunden</li>
                    <li>• Andere Gründe</li>
                </ul>
                <p class="text-sm text-blue-600 mt-4">
                    Ihr Feedback hilft uns, unseren Service zu verbessern. 
                    <a href="mailto:info@bekservice.de?subject=Job-Alert Feedback" class="text-primary-orange hover:underline">
                        Schreiben Sie uns gerne
                    </a>.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('jobs.search') }}" class="btn-primary">
                    Jobs durchsuchen
                </a>
                <a href="{{ route('job-alerts.create') }}" class="btn-outline">
                    Neuen Alert erstellen
                </a>
            </div>
        </div>

        <!-- Reactivation Info -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Wieder aktivieren?</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <p>
                    Falls Sie Ihre Meinung ändern, können Sie jederzeit einen neuen Job-Alert erstellen.
                </p>
                <p>
                    <strong>Tipp:</strong> Sie können auch die Benachrichtigungsfrequenz anpassen, 
                    um weniger E-Mails zu erhalten (z.B. wöchentlich statt täglich).
                </p>
                <div class="text-center mt-4">
                    <a href="{{ route('job-alerts.create') }}" class="text-primary-orange hover:underline font-medium">
                        → Neuen Job-Alert erstellen
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 