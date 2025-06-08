@extends('layouts.app')

@section('title', 'Bewerbung erfolgreich versendet - Ihre Stelle')
@section('description', 'Ihre Bewerbung wurde erfolgreich übermittelt. Vielen Dank für Ihr Interesse!')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-8">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Bewerbung erfolgreich versendet!</h1>
            <p class="text-xl text-gray-600 mb-8">
                Vielen Dank für Ihr Interesse an der Position
            </p>

            <!-- Job Info -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8 max-w-2xl mx-auto">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $job->title }}</h2>
                @if($job->arbeitsgeber_name)
                    <p class="text-gray-600 mb-2">{{ $job->arbeitsgeber_name }}</p>
                @endif
                @if($job->city)
                    <p class="text-gray-500">{{ $job->city }}</p>
                @endif
            </div>

            <!-- Next Steps -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8 max-w-3xl mx-auto text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 text-center">Wie geht es weiter?</h3>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">
                                1
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-medium text-gray-900">Bestätigung per E-Mail</h4>
                            <p class="text-gray-600">Sie erhalten in Kürze eine Bestätigungs-E-Mail mit allen Details Ihrer Bewerbung.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">
                                2
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-medium text-gray-900">Prüfung durch das Unternehmen</h4>
                            <p class="text-gray-600">Ihre Bewerbungsunterlagen werden vom Unternehmen sorgfältig geprüft.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">
                                3
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-medium text-gray-900">Rückmeldung</h4>
                            <p class="text-gray-600">Bei Interesse wird sich das Unternehmen direkt bei Ihnen melden. Dies kann einige Tage dauern.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-lg mx-auto">
                <a href="{{ route('jobs.show', $job) }}" 
                   class="bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-center">
                    Zurück zur Stellenanzeige
                </a>
                <a href="{{ route('jobs.search') }}" 
                   class="bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center">
                    Weitere Jobs suchen
                </a>
            </div>

            <!-- Contact Info -->
            <div class="mt-12 bg-gray-100 rounded-lg p-6 max-w-2xl mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Fragen zu Ihrer Bewerbung?</h3>
                <p class="text-gray-600 mb-4">
                    Bei Fragen zum Bewerbungsprozess oder technischen Problemen können Sie sich gerne an uns wenden:
                </p>
                <div class="text-gray-700">
                    <p>
                        <strong>E-Mail:</strong> 
                        <a href="mailto:info@bekservice.de" class="text-blue-600 hover:text-blue-800">info@bekservice.de</a>
                    </p>
                    <p>
                        <strong>Telefon:</strong> 
                        <a href="tel:+4983193065616" class="text-blue-600 hover:text-blue-800">(+49) 831 93065616</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 