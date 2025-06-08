@extends('layouts.app')

@section('title', 'Karriere-Tipps - Erfolg im Berufsleben - Ihre Stelle')
@section('description', 'Professionelle Karriere-Tipps und Ratschläge für Ihren beruflichen Erfolg.')

@section('content')
<div class="bg-white py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Karriere-Tipps</h1>
            <p class="mt-4 text-lg leading-6 text-gray-500">
                Wertvolle Tipps für Ihren beruflichen Erfolg
            </p>
        </div>

        <div class="mt-12 max-w-3xl mx-auto">
            <!-- Bewerbungsprozess -->
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Der erfolgreiche Bewerbungsprozess</h2>
                <div class="grid gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Vorbereitung der Bewerbung</h3>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li>Recherchieren Sie das Unternehmen gründlich</li>
                            <li>Analysieren Sie die Stellenanzeige im Detail</li>
                            <li>Passen Sie Ihre Unterlagen individuell an</li>
                            <li>Lassen Sie Ihre Bewerbung von anderen prüfen</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Das Vorstellungsgespräch</h3>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li>Bereiten Sie sich auf häufige Fragen vor</li>
                            <li>Üben Sie die Selbstpräsentation</li>
                            <li>Wählen Sie angemessene Kleidung</li>
                            <li>Seien Sie pünktlich und authentisch</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Karriereentwicklung -->
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Karriereentwicklung</h2>
                <div class="space-y-6">
                    <div class="bg-primary-orange/10 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Weiterbildung</h3>
                        <p class="text-gray-600 mb-4">
                            Kontinuierliche Weiterbildung ist der Schlüssel zum beruflichen Erfolg. Bleiben Sie in Ihrem
                            Fachgebiet auf dem neuesten Stand und erweitern Sie Ihre Kompetenzen.
                        </p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li>Fachspezifische Fortbildungen</li>
                            <li>Soft-Skill-Trainings</li>
                            <li>Sprachkurse</li>
                            <li>Digitale Kompetenzen</li>
                        </ul>
                    </div>

                    <div class="bg-primary-orange/10 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Netzwerken</h3>
                        <p class="text-gray-600 mb-4">
                            Ein starkes berufliches Netzwerk öffnet Türen und schafft neue Möglichkeiten.
                        </p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                            <li>Besuchen Sie Branchenveranstaltungen</li>
                            <li>Pflegen Sie Ihr LinkedIn-Profil</li>
                            <li>Bleiben Sie mit ehemaligen Kollegen in Kontakt</li>
                            <li>Engagieren Sie sich in Fachgruppen</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Work-Life-Balance -->
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Work-Life-Balance</h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="prose prose-lg text-gray-600">
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Setzen Sie klare Grenzen zwischen Arbeit und Privatleben</li>
                            <li>Planen Sie regelmäßige Pausen ein</li>
                            <li>Pflegen Sie Hobbys und soziale Kontakte</li>
                            <li>Achten Sie auf Ihre Gesundheit</li>
                            <li>Lernen Sie, "Nein" zu sagen</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Abschluss-Tipp -->
            <div class="bg-blue-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Unser Tipp</h2>
                <p class="text-gray-600">
                    Erfolg im Beruf bedeutet nicht nur fachliche Expertise, sondern auch persönliche Weiterentwicklung.
                    Bleiben Sie authentisch und setzen Sie sich realistische Ziele für Ihre Karriere.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 