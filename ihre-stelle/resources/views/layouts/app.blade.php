<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ihre Stelle')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <a href="/" class="text-lg font-semibold">Ihre Stelle</a>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4">
        @yield('content')
    </main>
</body>
</html>
