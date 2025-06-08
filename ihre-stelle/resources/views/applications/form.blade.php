@extends('layouts.app')

@section('title', 'Bewerbung für ' . $job->title . ' - Ihre Stelle')
@section('description', 'Bewerben Sie sich jetzt für die Position ' . $job->title . ' bei ' . ($job->arbeitsgeber_name ?? 'unserem Partner'))

@section('content')
<div class="hero-gradient min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8 fade-in">
            <h1 class="text-4xl font-bold text-primary-blue mb-4">Express-Bewerbung</h1>
            <div class="card-gradient rounded-lg p-6 mb-8 card-hover">
                <h2 class="text-xl font-semibold text-primary-blue mb-2">{{ $job->title }}</h2>
                @if($job->arbeitsgeber_name)
                    <p class="text-gray-600 mb-2">{{ $job->arbeitsgeber_name }}</p>
                @endif
                @if($job->city)
                    <p class="text-gray-500">{{ $job->city }}</p>
                @endif
            </div>
        </div>

        <!-- Form -->
        <div class="application-form slide-up">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Bitte korrigieren Sie folgende Fehler:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('application.store', $job) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Persönliche Daten</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="vorname" class="form-label">
                                Vorname <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="vorname" 
                                   name="vorname" 
                                   value="{{ old('vorname') }}"
                                   required
                                   class="form-input w-full @error('vorname') border-red-500 @enderror">
                        </div>
                        
                        <div>
                            <label for="name" class="form-label">
                                Nachname <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="form-input w-full @error('name') border-red-500 @enderror">
                        </div>
                        
                        <div>
                            <label for="email" class="form-label">
                                E-Mail-Adresse <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="form-input w-full @error('email') border-red-500 @enderror">
                        </div>
                        
                        <div>
                            <label for="telefon" class="form-label">
                                Telefonnummer
                            </label>
                            <input type="tel" 
                                   id="telefon" 
                                   name="telefon" 
                                   value="{{ old('telefon') }}"
                                   class="form-input w-full @error('telefon') border-red-500 @enderror">
                        </div>
                    </div>
                </div>

                <!-- Message -->
                <div>
                    <label for="nachricht" class="form-label">
                        Anschreiben / Nachricht <span class="text-red-500">*</span>
                    </label>
                    <textarea id="nachricht" 
                              name="nachricht" 
                              rows="6" 
                              required
                              placeholder="Erzählen Sie uns, warum Sie sich für diese Position interessieren und was Sie mitbringen..."
                              class="form-input w-full @error('nachricht') border-red-500 @enderror">{{ old('nachricht') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Mindestens 50 Zeichen</p>
                </div>

                <!-- File Uploads -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bewerbungsunterlagen</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="lebenslauf" class="form-label">
                                Lebenslauf <span class="text-red-500">*</span>
                            </label>
                            <div class="file-upload-area" id="lebenslauf-area">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="lebenslauf" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Datei auswählen</span>
                                            <input id="lebenslauf" name="lebenslauf" type="file" accept=".pdf,.doc,.docx" required class="sr-only">
                                        </label>
                                        <p class="pl-1">oder per Drag & Drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX bis zu 5MB</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="anschreiben" class="form-label">
                                Anschreiben (optional)
                            </label>
                            <div class="file-upload-area" id="anschreiben-area">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="anschreiben" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Datei auswählen</span>
                                            <input id="anschreiben" name="anschreiben" type="file" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">oder per Drag & Drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX bis zu 5MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy Consent -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="datenschutz" 
                                   name="datenschutz" 
                                   type="checkbox" 
                                   required
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded @error('datenschutz') border-red-500 @enderror">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="datenschutz" class="font-medium text-gray-700">
                                Datenschutzerklärung <span class="text-red-500">*</span>
                            </label>
                            <p class="text-gray-500">
                                Ich habe die <a href="{{ route('datenschutz') }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Datenschutzerklärung</a> gelesen und stimme der Verarbeitung meiner personenbezogenen Daten zum Zwecke des Bewerbungsverfahrens zu.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" 
                            class="flex-1 btn-primary py-3 px-6 rounded-lg font-semibold focus:outline-none">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Bewerbung absenden
                    </button>
                    <a href="{{ route('jobs.show', $job) }}" 
                       class="flex-1 sm:flex-none btn-secondary py-3 px-6 rounded-lg font-semibold focus:outline-none text-center">
                        Zurück zur Stellenanzeige
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Hinweise zur Bewerbung</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Ihre Bewerbung wird direkt an das Unternehmen weitergeleitet</li>
                            <li>Sie erhalten eine Bestätigung per E-Mail</li>
                            <li>Das Unternehmen wird sich bei Interesse direkt bei Ihnen melden</li>
                            <li>Ihre Daten werden vertraulich behandelt</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced file upload with drag & drop
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = ['lebenslauf', 'anschreiben'];
    
    fileInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        const uploadArea = document.getElementById(inputId + '-area');
        const label = input.parentElement;
        
        // File selection
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                updateFileDisplay(uploadArea, file);
            }
        });
        
        // Drag & Drop functionality
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                // Check file type
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (allowedTypes.includes(file.type)) {
                    input.files = files;
                    updateFileDisplay(uploadArea, file);
                } else {
                    alert('Bitte wählen Sie eine PDF-, DOC- oder DOCX-Datei aus.');
                }
            }
        });
    });
    
    function updateFileDisplay(area, file) {
        const span = area.querySelector('span');
        if (span) {
            span.textContent = file.name;
            span.classList.add('text-primary-orange');
        }
        area.classList.add('border-primary-orange');
    }
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<div class="spinner inline-block mr-2"></div>Wird gesendet...';
        submitBtn.disabled = true;
    });
});
</script>
@endsection 