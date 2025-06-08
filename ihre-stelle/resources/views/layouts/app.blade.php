<!DOCTYPE html>
<html lang="de" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ihre Stelle - Jobs finden leicht gemacht')</title>
    <meta name="description" content="@yield('description', 'Finden Sie Ihren Traumjob bei Ihre Stelle. Tausende aktuelle Stellenanzeigen aus allen Branchen.')">
    
    <!-- SEO and AI Optimization Meta Tags -->
    <meta name="keywords" content="@yield('keywords', 'Jobs, Stellenanzeigen, Karriere, Arbeit, Vollzeit, Teilzeit, Deutschland, Bewerbung')">
    <meta name="author" content="Ihre Stelle">
    <meta name="robots" content="index, follow">
    <meta name="language" content="de">
    <meta name="geo.region" content="DE">
    <meta name="geo.country" content="Germany">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Ihre Stelle - Jobs finden leicht gemacht')">
    <meta property="og:description" content="@yield('description', 'Finden Sie Ihren Traumjob bei Ihre Stelle. Tausende aktuelle Stellenanzeigen aus allen Branchen.')">
    <meta property="og:image" content="{{ asset('logo/ihre-stelle_logo_quer.png') }}">
    <meta property="og:site_name" content="Ihre Stelle">
    <meta property="og:locale" content="de_DE">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Ihre Stelle - Jobs finden leicht gemacht')">
    <meta property="twitter:description" content="@yield('description', 'Finden Sie Ihren Traumjob bei Ihre Stelle. Tausende aktuelle Stellenanzeigen aus allen Branchen.')">
    <meta property="twitter:image" content="{{ asset('logo/ihre-stelle_logo_quer.png') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Alternate languages -->
    <link rel="alternate" hreflang="de" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Mapbox CSS -->
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])

    <!-- Fallback CSS if Vite assets fail to load -->
    <script>
        // Check if Vite CSS loaded, if not load fallback
        setTimeout(function() {
            var viteCssLoaded = false;
            var stylesheets = document.querySelectorAll('link[rel="stylesheet"]');
            for (var i = 0; i < stylesheets.length; i++) {
                if (stylesheets[i].href.includes('custom-') || stylesheets[i].href.includes('app-')) {
                    viteCssLoaded = true;
                    break;
                }
            }
            
            if (!viteCssLoaded) {
                // Load Tailwind CSS from CDN as fallback
                var tailwindCSS = document.createElement('link');
                tailwindCSS.rel = 'stylesheet';
                tailwindCSS.href = 'https://cdn.tailwindcss.com';
                document.head.appendChild(tailwindCSS);
                
                // Load Ihre-Stelle fallback CSS
                var fallbackCSS = document.createElement('link');
                fallbackCSS.rel = 'stylesheet';
                fallbackCSS.href = '{{ asset("css/ihre-stelle-styles.css") }}';
                document.head.appendChild(fallbackCSS);
                
                console.log('Vite CSS failed to load, using fallback styles');
            }
        }, 1000);
    </script>

    <!-- Additional Styles -->
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    @stack('head')
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('logo/ihre-stelle_logo_quer.png') }}" 
                                     alt="Ihre Stelle" 
                                     class="h-10 w-auto">
                            </div>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="nav-link px-3 py-2 text-sm font-medium transition-colors">
                            Home
                        </a>
                        <a href="{{ route('jobs.search') }}" class="nav-link px-3 py-2 text-sm font-medium transition-colors">
                            Jobs suchen
                        </a>
                        <a href="#" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                            Job posten
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600" id="mobile-menu-button">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-200">
                    <a href="{{ route('home') }}" class="nav-link block px-3 py-2 text-base font-medium">Home</a>
                    <a href="{{ route('jobs.search') }}" class="nav-link block px-3 py-2 text-base font-medium">Jobs suchen</a>
                    <a href="#" class="btn-primary block px-3 py-2 rounded-lg text-base font-medium text-center">Job posten</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="mb-4">
                            <img src="{{ asset('logo/ihre-stelle_logo_quer-white.png') }}" 
                                 alt="Ihre Stelle" 
                                 class="h-8 w-auto">
                        </div>
                        <p class="text-gray-300 mb-4">
                            Die führende Jobbörse für qualifizierte Fachkräfte. 
                            Finden Sie Ihren Traumjob oder die perfekten Kandidaten.
                        </p>
                        
                        <!-- Company Info -->
                        <div class="text-gray-300 text-sm mb-4">
                            <p class="font-semibold text-white mb-2">BEK Service GmbH</p>
                            <p>Westendstr. 2A<br>D-87439 Kempten (Allgäu)</p>
                            <p class="mt-2">
                                E: <a href="mailto:info@bekservice.de" class="text-blue-400 hover:text-blue-300">info@bekservice.de</a><br>
                                P: <a href="tel:+4983193065616" class="text-blue-400 hover:text-blue-300">(+49) 831 93065616</a>
                            </p>
                        </div>
                        
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" title="Facebook">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" title="Instagram">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.297-3.323C5.902 8.198 7.053 7.708 8.35 7.708s2.448.49 3.323 1.297c.897.875 1.387 2.026 1.387 3.323s-.49 2.448-1.297 3.323c-.875.897-2.026 1.387-3.323 1.387zm7.718 0c-1.297 0-2.448-.49-3.323-1.297-.897-.875-1.387-2.026-1.387-3.323s.49-2.448 1.297-3.323c.875-.897 2.026-1.387 3.323-1.387s2.448.49 3.323 1.297c.897.875 1.387 2.026 1.387 3.323s-.49 2.448-1.297 3.323c-.875.897-2.026 1.387-3.323 1.387z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" title="LinkedIn">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" title="YouTube">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" title="Xing">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.188 0c-.517 0-.741.325-.927.66 0 0-7.455 13.224-7.702 13.657.284.52 4.56 8.668 4.56 8.668.214.364.461.677.927.677h3.905c.284 0 .463-.113.463-.31 0-.084-.03-.175-.097-.298l-4.678-8.766L21.297 1.55c.085-.17.127-.316.127-.426 0-.197-.184-.297-.475-.297h-3.761zm-9.734 7.618c-.179 0-.345.113-.345.31 0 .084.03.175.097.298l2.838 5.241-4.676 8.766c-.085.17-.127.316-.127.426 0 .197.184.297.475.297h3.761c.517 0 .741-.325.927-.66 0 0 4.78-8.956 4.78-8.956l-2.993-5.501s-.175-.31-.927-.31H8.454z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Für Jobsuchende</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li><a href="{{ route('jobs.search') }}" class="hover:text-white transition-colors">Jobs suchen</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Karriere-Tipps</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Lebenslauf-Hilfe</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Gehaltsvergleich</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Für Arbeitgeber</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li><a href="#" class="hover:text-white transition-colors">Job posten</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Kandidaten suchen</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Preise</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Kontakt</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Rechtliches</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li><a href="{{ route('impressum') }}" class="hover:text-white transition-colors">Impressum</a></li>
                            <li><a href="{{ route('datenschutz') }}" class="hover:text-white transition-colors">Datenschutz</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">AGB</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Cookie-Richtlinie</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-8 pt-8 border-t border-gray-800">
                    <div class="flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm">
                        <div class="mb-4 md:mb-0">
                            <p>&copy; {{ date('Y') }} BEK Service GmbH. Alle Rechte vorbehalten.</p>
                            <p class="mt-1">HRB: 14544 | Steuer-Nr.: 127/122/20552 | USt-IdNr.: DE322920054</p>
                        </div>
                        <div class="text-center md:text-right">
                            <p>Geschäftsführer: Iman Khabazi Koma</p>
                            <p class="mt-1">Registergericht: Kempten (Allgäu)</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
