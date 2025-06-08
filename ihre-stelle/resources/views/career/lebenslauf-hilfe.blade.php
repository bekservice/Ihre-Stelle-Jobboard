@extends('layouts.app')

@section('title', 'Lebenslauf-Hilfe - Tipps für den perfekten CV - Ihre Stelle')
@section('description', 'Professionelle Tipps und Anleitungen für die Erstellung eines überzeugenden Lebenslaufs.')

@section('content')
<div class="bg-white py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Lebenslauf-Hilfe</h1>
            <p class="mt-4 text-lg leading-6 text-gray-500">
                So erstellen Sie einen überzeugenden Lebenslauf
            </p>
        </div>

        <div class="mt-12 max-w-3xl mx-auto">
            <!-- Grundlegende Struktur -->
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Die perfekte Struktur</h2>
                <div class="prose prose-lg text-gray-600">
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Persönliche Daten (Name, Kontakt, etc.)</li>
                        <li>Beruflicher Werdegang (chronologisch rückwärts)</li>
                        <li>Ausbildung und Qualifikationen</li>
                        <li>Zusatzqualifikationen und Weiterbildungen</li>
                        <li>Sprachkenntnisse</li>
                        <li>IT-Kenntnisse</li>
                        <li>Hobbys und Interessen (optional)</li>
                    </ul>
                </div>
            </div>

            <!-- Wichtige Tipps -->
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Die wichtigsten Tipps</h2>
                <div class="grid gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">1. Übersichtlichkeit</h3>
                        <p class="text-gray-600">Achten Sie auf eine klare Struktur und ein einheitliches Layout. Verwenden Sie maximal zwei verschiedene Schriftarten.</p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">2. Aktualität</h3>
                        <p class="text-gray-600">Der Lebenslauf sollte auf dem neuesten Stand sein. Beginnen Sie mit der aktuellsten Position.</p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">3. Relevanz</h3>
                        <p class="text-gray-600">Passen Sie Ihren Lebenslauf an die jeweilige Stelle an. Heben Sie relevante Erfahrungen hervor.</p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">4. Professionalität</h3>
                        <p class="text-gray-600">Verwenden Sie ein professionelles Foto und achten Sie auf fehlerfreie Rechtschreibung.</p>
                    </div>
                </div>
            </div>

            <!-- Häufige Fehler -->
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Diese Fehler sollten Sie vermeiden</h2>
                <div class="bg-red-50 p-6 rounded-lg">
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Zu lange und unübersichtliche Formulierungen</li>
                        <li>Lücken im Lebenslauf ohne Erklärung</li>
                        <li>Veraltete oder irrelevante Informationen</li>
                        <li>Rechtschreibfehler und Tippfehler</li>
                        <li>Unprofessionelles Foto oder schlechte Formatierung</li>
                    </ul>
                </div>
            </div>

            <!-- Abschluss -->
            <div class="bg-primary-orange/10 p-6 rounded-lg">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Tipp für Ihre Bewerbung</h2>
                <p class="text-gray-600">
                    Speichern Sie Ihren Lebenslauf immer als PDF, um sicherzustellen, dass die Formatierung bei allen Empfängern gleich aussieht.
                    Benennen Sie die Datei professionell, zum Beispiel: "Lebenslauf_Max_Mustermann.pdf"
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 