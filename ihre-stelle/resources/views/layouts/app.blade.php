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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
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

    <!-- Cookie Consent & GDPR Script (Cookie Information) -->
    <script>
    // Definieren Sie dataLayer und die gtag-Funktion.
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    // WICHTIG – Regionenliste für EU setzen (ISO 3166-2-Codes)
    gtag('consent', 'default', {
      'ad_storage': 'denied',
      'ad_user_data': 'denied',
      'ad_personalization': 'denied',
      'analytics_storage': 'denied',
      'regions':['AT','BE','BG','HR','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IE','IT','LV','LT','LU','MT','NL','PL','PT','RO','SK','SI','ES','SE']
    });
    // Standardeinwilligung für andere Regionen
    gtag('consent', 'default', {
      'ad_storage': 'granted',
      'ad_user_data': 'granted',
      'ad_personalization': 'granted',
      'analytics_storage': 'granted'
    });
    </script>
    <!-- Cookie Banner (Apple-Style, modern, minimalistisch, mit Einstellungen und Ablehnen) -->
    <style>
      .cookie-banner {
        position: fixed;
        left: 50%;
        bottom: 32px;
        transform: translateX(-50%);
        background: rgba(255,255,255,0.95);
        box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        border-radius: 24px;
        padding: 2rem 2.5rem 2rem 2.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 9999;
        min-width: 320px;
        max-width: 90vw;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        animation: fadeInUp 0.7s cubic-bezier(.23,1.01,.32,1) 0.2s both;
      }
      .cookie-banner h3 {
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #222;
      }
      .cookie-banner p {
        color: #444;
        font-size: 1rem;
        margin-bottom: 1.2rem;
        text-align: center;
      }
      .cookie-banner .cookie-btn, .cookie-banner .cookie-settings-btn, .cookie-banner .cookie-reject-btn {
        border: none;
        border-radius: 16px;
        padding: 0.7rem 2.2rem;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        margin: 0 0.3rem;
        transition: background 0.2s, color 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
      }
      .cookie-banner .cookie-btn {
        background: #222;
        color: #fff;
      }
      .cookie-banner .cookie-btn:hover {
        background: #111;
      }
      .cookie-banner .cookie-settings-btn {
        background: #f5f5f7;
        color: #222;
        border: 1px solid #e5e5e5;
      }
      .cookie-banner .cookie-settings-btn:hover {
        background: #e5e5e5;
      }
      .cookie-banner .cookie-reject-btn {
        background: #fff;
        color: #d32d2f;
        border: 1px solid #d32d2f;
      }
      .cookie-banner .cookie-reject-btn:hover {
        background: #ffeaea;
      }
      .cookie-banner .cookie-btn-row {
        display: flex;
        flex-direction: row;
        gap: 0.5rem;
        justify-content: center;
      }
      @keyframes fadeInUp {
        from { opacity: 0; transform: translate(-50%, 40px); }
        to { opacity: 1; transform: translate(-50%, 0); }
      }
      @media (max-width: 600px) {
        .cookie-banner { padding: 1.2rem 1rem; min-width: 0; }
        .cookie-banner h3 { font-size: 1rem; }
        .cookie-banner p { font-size: 0.95rem; }
      }
      /* Modal Styles */
      .cookie-modal-bg {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.25);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeInModal 0.3s;
      }
      .cookie-modal {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        padding: 2rem 2.5rem;
        max-width: 95vw;
        min-width: 320px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        animation: fadeInUp 0.5s cubic-bezier(.23,1.01,.32,1) 0.1s both;
      }
      .cookie-modal h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #222;
      }
      .cookie-modal label {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #444;
      }
      .cookie-modal input[type='checkbox'] {
        margin-right: 0.7rem;
        accent-color: #0071e3;
      }
      .cookie-modal .cookie-modal-btn-row {
        display: flex;
        gap: 0.7rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
      }
      .cookie-modal .cookie-btn {
        padding: 0.6rem 1.8rem;
        font-size: 1rem;
      }
      @keyframes fadeInModal {
        from { opacity: 0; }
        to { opacity: 1; }
      }
    </style>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        if (!localStorage.getItem('cookieConsentGiven')) {
          var banner = document.createElement('div');
          banner.className = 'cookie-banner';
          banner.innerHTML = `
            <h3>🍪 Wir respektieren Ihre Privatsphäre</h3>
            <p>Wir verwenden Cookies, um Ihr Erlebnis zu verbessern. Sie können selbst entscheiden, welche Cookies Sie zulassen. Essenzielle Cookies sind für den Betrieb der Seite notwendig. <a href='/legal/datenschutz' style='color:#0071e3;text-decoration:underline;'>Mehr erfahren</a></p>
            <div class='cookie-btn-row'>
              <button class='cookie-btn'>OK</button>
              <button class='cookie-settings-btn'>Einstellungen</button>
              <button class='cookie-reject-btn'>Ablehnen</button>
            </div>
          `;
          document.body.appendChild(banner);

          // OK Button
          banner.querySelector('.cookie-btn').onclick = function() {
            localStorage.setItem('cookieConsentGiven', '1');
            localStorage.setItem('cookieConsentAnalytics', '1');
            localStorage.setItem('cookieConsentMarketing', '1');
            banner.style.opacity = '0';
            setTimeout(function(){ banner.remove(); }, 400);
          };
          // Ablehnen Button
          banner.querySelector('.cookie-reject-btn').onclick = function() {
            localStorage.setItem('cookieConsentGiven', '1');
            localStorage.setItem('cookieConsentAnalytics', '0');
            localStorage.setItem('cookieConsentMarketing', '0');
            banner.style.opacity = '0';
            setTimeout(function(){ banner.remove(); }, 400);
          };
          // Einstellungen Button
          banner.querySelector('.cookie-settings-btn').onclick = function() {
            showCookieModal();
          };
        }

        function showCookieModal() {
          var modalBg = document.createElement('div');
          modalBg.className = 'cookie-modal-bg';
          modalBg.innerHTML = `
            <div class='cookie-modal'>
              <h4>Cookie-Einstellungen</h4>
              <form id='cookie-settings-form'>
                <label><input type='checkbox' checked disabled> Essenzielle Cookies (immer aktiv)</label>
                <label><input type='checkbox' id='analytics-cb'> Analyse & Statistik</label>
                <label><input type='checkbox' id='marketing-cb'> Marketing & Personalisierung</label>
                <div class='cookie-modal-btn-row'>
                  <button type='button' class='cookie-btn' id='cookie-save-btn'>Speichern</button>
                  <button type='button' class='cookie-settings-btn' id='cookie-cancel-btn'>Abbrechen</button>
                </div>
              </form>
            </div>
          `;
          document.body.appendChild(modalBg);
          // Vorbelegen
          document.getElementById('analytics-cb').checked = localStorage.getItem('cookieConsentAnalytics') !== '0';
          document.getElementById('marketing-cb').checked = localStorage.getItem('cookieConsentMarketing') !== '0';
          // Speichern
          document.getElementById('cookie-save-btn').onclick = function() {
            localStorage.setItem('cookieConsentGiven', '1');
            localStorage.setItem('cookieConsentAnalytics', document.getElementById('analytics-cb').checked ? '1' : '0');
            localStorage.setItem('cookieConsentMarketing', document.getElementById('marketing-cb').checked ? '1' : '0');
            modalBg.style.opacity = '0';
            setTimeout(function(){ modalBg.remove(); }, 300);
            var banner = document.querySelector('.cookie-banner');
            if(banner) { banner.style.opacity = '0'; setTimeout(function(){ banner.remove(); }, 400); }
          };
          // Abbrechen
          document.getElementById('cookie-cancel-btn').onclick = function() {
            modalBg.style.opacity = '0';
            setTimeout(function(){ modalBg.remove(); }, 300);
          };
        }
      });
    </script>

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
                        <a href="{{ route('jobs.search') }}" class="nav-link px-3 py-2 text-sm font-medium transition-colors">
                            Jobs suchen
                        </a>
                        
                        <!-- Job-Alert Icon -->
                        <a href="{{ route('job-alerts.create') }}" class="p-2 text-gray-600 hover:text-primary-orange transition-colors" title="Job-Alert erstellen">
                            <i class="fa-solid fa-bell text-xl"></i>
                        </a>
                        
                        <!-- Gespeicherte Jobs Icon -->
                        <button id="saved-jobs-btn" class="relative p-2 text-gray-600 hover:text-primary-orange transition-colors" title="Gespeicherte Jobs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            <!-- Badge für Anzahl gespeicherter Jobs -->
                            <span id="saved-jobs-count" class="absolute -top-1 -right-1 bg-primary-orange text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                        </button>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center space-x-2">
                        <!-- Job-Alert Icon Mobile -->
                        <a href="{{ route('job-alerts.create') }}" class="p-2 text-gray-600 hover:text-primary-orange transition-colors" title="Job-Alert erstellen">
                            <i class="fa-solid fa-bell text-xl"></i>
                        </a>
                        
                        <!-- Gespeicherte Jobs Icon Mobile -->
                        <button id="saved-jobs-btn-mobile" class="relative p-2 text-gray-600 hover:text-primary-orange transition-colors" title="Gespeicherte Jobs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            <!-- Badge für Anzahl gespeicherter Jobs -->
                            <span id="saved-jobs-count-mobile" class="absolute -top-1 -right-1 bg-primary-orange text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                        </button>
                        
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
                  <!--  <a href="#" class="btn-primary block px-3 py-2 rounded-lg text-base font-medium text-center">Job posten</a> -->
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
                            <li><a href="{{ route('karriere-tipps') }}" class="hover:text-white transition-colors">Karriere-Tipps</a></li>
                            <li><a href="{{ route('lebenslauf-hilfe') }}" class="hover:text-white transition-colors">Lebenslauf-Hilfe</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Gehaltsvergleich</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Für Arbeitgeber</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li><a href="{{ route('post-job') }}" class="hover:text-white transition-colors">Job posten</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Kandidaten suchen</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Preise</a></li>
                            <li><a href="{{ route('kontakt') }}" class="hover:text-white transition-colors">Kontakt</a></li>
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

        // Gespeicherte Jobs Funktionalität
        document.addEventListener('DOMContentLoaded', function() {
            updateSavedJobsCount();
            
            // Event Listeners für beide Buttons (Desktop und Mobile)
            const savedJobsButtons = document.querySelectorAll('#saved-jobs-btn, #saved-jobs-btn-mobile');
            savedJobsButtons.forEach(button => {
                button.addEventListener('click', function() {
                    showSavedJobsModal();
                });
            });
        });

        function updateSavedJobsCount() {
            const savedJobs = JSON.parse(localStorage.getItem('savedJobs') || '[]');
            const count = savedJobs.length;
            
            const countElements = document.querySelectorAll('#saved-jobs-count, #saved-jobs-count-mobile');
            countElements.forEach(element => {
                element.textContent = count;
                if (count > 0) {
                    element.classList.remove('hidden');
                } else {
                    element.classList.add('hidden');
                }
            });
        }

        function showSavedJobsModal() {
            const savedJobs = JSON.parse(localStorage.getItem('savedJobs') || '[]');
            
            if (savedJobs.length === 0) {
                showNotification('Keine gespeicherten Jobs vorhanden', 'info');
                return;
            }

            // Fetch job details for saved jobs
            fetchSavedJobDetails(savedJobs);
        }

        function fetchSavedJobDetails(jobIds) {
            // Create modal with loading state
            const modal = document.createElement('div');
            modal.className = 'saved-jobs-modal';
            modal.innerHTML = `
                <div class="saved-jobs-modal-content">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Gespeicherte Jobs (${jobIds.length})</h3>
                        <button id="close-saved-jobs-modal" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div id="saved-jobs-list" class="space-y-4">
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-orange mx-auto"></div>
                            <p class="text-gray-600 mt-3 font-medium">Lade gespeicherte Jobs...</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center">
                        <button onclick="clearAllSavedJobs()" class="text-red-600 hover:text-red-800 text-sm font-medium hover:bg-red-50 px-3 py-2 rounded-lg transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Alle löschen
                        </button>
                        <a href="/jobs" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                            Weitere Jobs finden
                        </a>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Modal schließen
            document.getElementById('close-saved-jobs-modal').addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Modal schließen bei Klick außerhalb
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });

            // Fetch real job data from server
            fetch('/api/saved-jobs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ job_ids: jobIds })
            })
            .then(response => response.json())
            .then(jobs => {
                displaySavedJobs(jobs, jobIds);
            })
            .catch(error => {
                console.error('Error fetching saved jobs:', error);
                displaySavedJobsError();
            });
        }

        function displaySavedJobs(jobs, originalJobIds) {
            const listContainer = document.getElementById('saved-jobs-list');
            
            if (jobs.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Keine aktiven Jobs gefunden</h4>
                        <p class="text-gray-600 mb-4">Die gespeicherten Jobs sind möglicherweise nicht mehr verfügbar.</p>
                        <button onclick="clearAllSavedJobs()" class="btn-outline px-4 py-2 rounded-lg text-sm font-medium">
                            Gespeicherte Jobs bereinigen
                        </button>
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = jobs.map(job => `
                <div class="saved-job-item bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg hover:border-primary-orange/30 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        ${job.logo_url ? `
                            <div class="flex-shrink-0">
                                <img src="${job.logo_url}" alt="Logo" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                            </div>
                        ` : `
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-primary-orange to-accent-orange rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                </svg>
                            </div>
                        `}
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 mb-2 text-lg leading-tight">
                                <a href="${job.url}" class="hover:text-primary-orange transition-colors line-clamp-2">
                                    ${job.title}
                                </a>
                            </h4>
                            
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                ${job.company ? `
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="font-medium">${job.company}</span>
                                    </div>
                                ` : ''}
                                
                                ${job.location ? `
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>${job.location}</span>
                                    </div>
                                ` : ''}
                                
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>${job.created_at}</span>
                                </div>
                            </div>
                            
                            ${job.category ? `
                                <div class="mb-3">
                                    <span class="inline-block bg-primary-orange/10 text-primary-orange text-xs font-medium px-2.5 py-1 rounded-full">
                                        ${job.category}
                                    </span>
                                </div>
                            ` : ''}
                            
                            <div class="flex items-center gap-3">
                                <a href="${job.url}" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                                    Details ansehen
                                </a>
                                <button onclick="removeSavedJob('${job.id}')" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Aus gespeicherten Jobs entfernen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function displaySavedJobsError() {
            const listContainer = document.getElementById('saved-jobs-list');
            listContainer.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Fehler beim Laden</h4>
                    <p class="text-gray-600 mb-4">Die gespeicherten Jobs konnten nicht geladen werden.</p>
                    <button onclick="location.reload()" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                        Erneut versuchen
                    </button>
                </div>
            `;
        }

        function removeSavedJob(jobId) {
            let savedJobs = JSON.parse(localStorage.getItem('savedJobs') || '[]');
            savedJobs = savedJobs.filter(id => id !== jobId);
            localStorage.setItem('savedJobs', JSON.stringify(savedJobs));
            
            updateSavedJobsCount();
            
            // Remove from modal if open
            const jobItem = document.querySelector(`[onclick="removeSavedJob('${jobId}')"]`)?.closest('.saved-job-item');
            if (jobItem) {
                jobItem.remove();
            }
            
            // Update modal title
            const modalTitle = document.querySelector('.saved-jobs-modal h3');
            if (modalTitle) {
                modalTitle.textContent = `Gespeicherte Jobs (${savedJobs.length})`;
            }
            
            // Close modal if no jobs left
            if (savedJobs.length === 0) {
                const modal = document.querySelector('.saved-jobs-modal');
                if (modal) {
                    document.body.removeChild(modal);
                }
                showNotification('Alle gespeicherten Jobs entfernt', 'success');
            } else {
                showNotification('Job entfernt', 'success');
            }
        }

        function clearAllSavedJobs() {
            if (confirm('Möchten Sie wirklich alle gespeicherten Jobs löschen?')) {
                localStorage.removeItem('savedJobs');
                updateSavedJobsCount();
                
                const modal = document.querySelector('.saved-jobs-modal');
                if (modal) {
                    document.body.removeChild(modal);
                }
                
                showNotification('Alle gespeicherten Jobs gelöscht', 'success');
            }
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Listen for storage changes to update count across tabs
        window.addEventListener('storage', function(e) {
            if (e.key === 'savedJobs') {
                updateSavedJobsCount();
            }
        });
    </script>

    <!-- Styles für Gespeicherte Jobs Modal -->
    <style>
        .saved-jobs-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .saved-jobs-modal-content {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            max-width: 700px;
            width: 95%;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .saved-job-item {
            transition: all 0.3s ease;
        }

        .saved-job-item:hover {
            transform: translateY(-2px);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Scrollbar styling */
        .saved-jobs-modal-content::-webkit-scrollbar {
            width: 6px;
        }

        .saved-jobs-modal-content::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .saved-jobs-modal-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .saved-jobs-modal-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            color: white;
            font-weight: 500;
            z-index: 1001;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background-color: #10b981;
        }

        .notification.error {
            background-color: #ef4444;
        }

        .notification.info {
            background-color: #3b82f6;
        }
    </style>
</body>
</html>
