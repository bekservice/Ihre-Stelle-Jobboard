@extends('layouts.app')

@section('title', 'Kontakt - Ihre Stelle')
@section('description', 'Kontaktieren Sie uns bei Ihre Stelle. Wir sind für Sie da.')

@section('content')
<div class="bg-white py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Kontakt</h1>
            <p class="mt-4 text-lg leading-6 text-gray-500">
                Wir freuen uns auf Ihre Nachricht
            </p>
        </div>
        
        <div class="mt-12 max-w-lg mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg divide-y divide-gray-200">
                <div class="px-6 py-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">BEK Service GmbH</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Adresse</h3>
                            <p class="mt-2 text-gray-600">
                                Westendstr. 2A<br>
                                D-87439 Kempten (Allgäu)
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Kontaktdaten</h3>
                            <div class="mt-2 text-gray-600">
                                <p>E-Mail: <a href="mailto:info@bekservice.de" class="text-primary-orange hover:text-primary-orange-dark">info@bekservice.de</a></p>
                                <p>Telefon: <a href="tel:+4983193065616" class="text-primary-orange hover:text-primary-orange-dark">(+49) 831 93065616</a></p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Unternehmensdaten</h3>
                            <div class="mt-2 text-gray-600">
                                <p>HRB: 14544</p>
                                <p>Steuer-Nr.: 127/122/20552</p>
                                <p>USt-IdNr.: DE322920054</p>
                                <p>Registergericht: Kempten (Allgäu)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 