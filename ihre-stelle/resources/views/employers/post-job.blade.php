@extends('layouts.app')

@section('title', 'Stellenanzeigen schalten - Multikanal-Jobmarketing im Allgäu | Ihre Stelle')
@section('description', 'Ihre Stellenanzeige auf über 15 Plattformen ✓ 1 Jahr Laufzeit ✓ Social Media Marketing ✓ Newsletter-Versand ✓ Persönliche Bewerberansprache ✓ Faire Festpreise')
@section('keywords', 'Stellenanzeige schalten, Multikanal-Recruiting, Jobs veröffentlichen Allgäu, Personalsuche Kempten, Stellenmarkt Allgäu, Jobbörse Allgäu')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-gray-900 to-primary-orange py-20 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl">
                Finden Sie Ihre idealen Mitarbeiter im Allgäu
            </h1>
            <p class="mt-6 max-w-2xl mx-auto text-xl text-white">
                Erreichen Sie mit Ihrer Stellenanzeige gezielt qualifizierte Fachkräfte in der Region. 
                Profitieren Sie von unserer lokalen Expertise und großen Reichweite.
            </p>
            <div class="mt-10">
                <a href="mailto:info@bekservice.de" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-medium rounded-md text-gray-900 bg-white hover:bg-gray-50 transition-colors shadow-lg">
                    Jetzt Stelle inserieren
                    <svg class="ml-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Vorteile Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Ihre Vorteile bei Ihre-Stelle.de</h2>
            <p class="mt-4 text-lg text-gray-600">
                Profitieren Sie von unserer langjährigen Erfahrung und unserem starken regionalen Netzwerk
            </p>
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Vorteil 1 -->
                <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
                    <div class="text-primary-orange mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Starke regionale Präsenz</h3>
                    <p class="text-gray-600">
                        Ihre Stellenanzeige erreicht gezielt Fachkräfte im Allgäu und Umgebung. 
                        Profitieren Sie von unserer etablierten Position als führende regionale Jobbörse.
                    </p>
                </div>

                <!-- Vorteil 2 -->
                <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
                    <div class="text-primary-orange mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Persönliche Beratung</h3>
                    <p class="text-gray-600">
                        Unser erfahrenes Team unterstützt Sie bei der optimalen Gestaltung Ihrer Stellenanzeige 
                        und berät Sie zu allen Fragen der Personalsuche.
                    </p>
                </div>

                <!-- Vorteil 3 -->
                <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
                    <div class="text-primary-orange mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Schnelle Besetzung</h3>
                    <p class="text-gray-600">
                        Durch unsere hohe Reichweite und aktive Kandidatendatenbank finden Sie schnell 
                        die passenden Mitarbeiter für Ihr Unternehmen.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Multichannel Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Maximale Reichweite durch Multikanal-Marketing</h2>
            <p class="mt-4 text-lg text-gray-600">
                Ein Preis - maximale Sichtbarkeit auf allen relevanten Plattformen
            </p>
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Jobportale -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="text-primary-orange mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">15+ Jobportale</h3>
                    <p class="text-gray-600">
                        Ihre Stelle erscheint automatisch auf über 15 führenden Jobportalen. Ein Posting - maximale Reichweite.
                    </p>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="text-primary-orange mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Social Media Marketing</h3>
                    <p class="text-gray-600">
                        Gezielte Verbreitung in Facebook und spezialisierten Fachgruppen. Erreichen Sie auch passive Jobsuchende.
                    </p>
                </div>

                <!-- Mobile Apps -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="text-primary-orange mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Mobile Apps</h3>
                    <p class="text-gray-600">
                        Präsenz in modernen Job-Apps wie Job Swipe. Erreichen Sie mobile Kandidaten direkt auf ihrem Smartphone.
                    </p>
                </div>

                <!-- E-Mail Marketing -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="text-primary-orange mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Personalisierte E-Mails</h3>
                    <p class="text-gray-600">
                        Direkter Versand an unsere qualifizierte Bewerberdatenbank. Newsletter und personalisierte Matching-E-Mails.
                    </p>
                </div>

                <!-- Laufzeit -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="text-primary-orange mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">1 Jahr Laufzeit</h3>
                    <p class="text-gray-600">
                        Volle Flexibilität mit 365 Tagen Laufzeit zum Festpreis. Keine versteckten Kosten oder Verlängerungen.
                    </p>
                </div>

                <!-- Support -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="text-primary-orange mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Persönliche Betreuung</h3>
                    <p class="text-gray-600">
                        Ihr persönlicher Ansprechpartner unterstützt Sie bei allen Fragen und optimiert Ihre Reichweite.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preisvorteile Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Transparent und fair</h2>
            <p class="mt-4 text-lg text-gray-600">
                Ein Preis - alle Leistungen inklusive
            </p>
        </div>

        <div class="mt-12 bg-primary-orange/10 rounded-xl p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Im Preis enthalten:</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="ml-3 text-gray-600">Veröffentlichung auf 15+ Jobportalen</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="ml-3 text-gray-600">Social Media Marketing in relevanten Gruppen</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="ml-3 text-gray-600">Integration in Job-Apps</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="ml-3 text-gray-600">Newsletter-Marketing</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="ml-3 text-gray-600">365 Tage Laufzeit</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="ml-3 text-gray-600">Persönliche Beratung & Support</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Prozess Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">So einfach geht's</h2>
            <p class="mt-4 text-lg text-gray-600">
                In nur wenigen Schritten zu Ihrer Stellenanzeige
            </p>
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Schritt 1 -->
                <div class="relative">
                    <div class="absolute top-0 left-0 -ml-8 hidden md:block">
                        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-primary-orange text-white text-2xl font-bold">1</span>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Kontakt aufnehmen</h3>
                        <p class="text-gray-600">
                            Schreiben Sie uns eine E-Mail an info@bekservice.de oder nutzen Sie unser Kontaktformular.
                        </p>
                    </div>
                </div>

                <!-- Schritt 2 -->
                <div class="relative">
                    <div class="absolute top-0 left-0 -ml-8 hidden md:block">
                        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-primary-orange text-white text-2xl font-bold">2</span>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Beratungsgespräch</h3>
                        <p class="text-gray-600">
                            Wir besprechen Ihre Anforderungen und erstellen ein individuelles Angebot.
                        </p>
                    </div>
                </div>

                <!-- Schritt 3 -->
                <div class="relative">
                    <div class="absolute top-0 left-0 -ml-8 hidden md:block">
                        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-primary-orange text-white text-2xl font-bold">3</span>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Veröffentlichung</h3>
                        <p class="text-gray-600">
                            Ihre Stellenanzeige geht online und erreicht qualifizierte Kandidaten.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Warum Ihre-Stelle Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900">Warum Ihre-Stelle.de?</h2>
            <p class="mt-4 text-lg text-gray-600">
                Ihre regionale Jobbörse mit persönlichem Service
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <ul class="space-y-8">
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">Lokale Expertise</h4>
                            <p class="mt-2 text-gray-600">
                                Als Unternehmen aus dem Allgäu kennen wir den regionalen Arbeitsmarkt 
                                und die Bedürfnisse der Arbeitgeber und Arbeitnehmer.
                            </p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">Qualifizierte Kandidaten</h4>
                            <p class="mt-2 text-gray-600">
                                Unsere Plattform wird von qualifizierten Fachkräften aus der Region 
                                aktiv für die Jobsuche genutzt.
                            </p>
                        </div>
                    </li>
                </ul>
            </div>

            <div>
                <ul class="space-y-8">
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">Optimale Sichtbarkeit</h4>
                            <p class="mt-2 text-gray-600">
                                Ihre Stellenanzeige wird über verschiedene Kanäle beworben und 
                                erreicht so die relevante Zielgruppe.
                            </p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-gray-900">Faire Konditionen</h4>
                            <p class="mt-2 text-gray-600">
                                Transparente Preise und flexible Laufzeiten - wir finden das 
                                passende Paket für Ihre Bedürfnisse.
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-primary-orange">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
            <span class="block">Bereit, Ihre Stelle zu besetzen?</span>
            <span class="block text-white/90 text-2xl mt-3">Kontaktieren Sie uns noch heute!</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
            <div class="inline-flex rounded-md shadow">
                <a href="mailto:info@bekservice.de" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary-orange bg-white hover:bg-gray-50">
                    Jetzt E-Mail senden
                </a>
            </div>
            <div class="ml-3 inline-flex rounded-md shadow">
                <a href="{{ route('kontakt') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-orange-dark hover:bg-primary-orange-darker">
                    Mehr erfahren
                </a>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Häufig gestellte Fragen</h2>
        </div>

        <div class="mt-12 max-w-3xl mx-auto">
            <dl class="space-y-8">
                <div>
                    <dt class="text-lg font-medium text-gray-900">
                        Wie lange ist die Laufzeit einer Stellenanzeige?
                    </dt>
                    <dd class="mt-2 text-gray-600">
                        Die Laufzeit können Sie flexibel wählen. Wir bieten verschiedene Pakete an, 
                        die wir gerne individuell mit Ihnen besprechen.
                    </dd>
                </div>

                <div>
                    <dt class="text-lg font-medium text-gray-900">
                        Wie schnell wird meine Anzeige veröffentlicht?
                    </dt>
                    <dd class="mt-2 text-gray-600">
                        Nach Abstimmung der Details und Freigabe kann Ihre Anzeige innerhalb 
                        von 24 Stunden online gehen.
                    </dd>
                </div>

                <div>
                    <dt class="text-lg font-medium text-gray-900">
                        Welche Reichweite hat meine Stellenanzeige?
                    </dt>
                    <dd class="mt-2 text-gray-600">
                        Ihre Anzeige erreicht Fachkräfte im gesamten Allgäu und den angrenzenden Regionen. 
                        Zusätzlich wird sie über verschiedene Partner-Netzwerke verbreitet.
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Kontakt Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-900">Kontaktieren Sie uns</h2>
            <p class="mt-4 text-lg text-gray-600">
                Unser Team steht Ihnen für alle Fragen zur Verfügung
            </p>
            
            <div class="mt-8 space-y-4 text-lg">
                <p>
                    <strong>BEK Service GmbH</strong><br>
                    Westendstr. 2A<br>
                    D-87439 Kempten (Allgäu)
                </p>
                <p>
                    <a href="mailto:info@bekservice.de" class="text-primary-orange hover:text-primary-orange-dark">
                        info@bekservice.de
                    </a>
                </p>
                <p>
                    <a href="tel:+4983193065616" class="text-primary-orange hover:text-primary-orange-dark">
                        (+49) 831 93065616
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 