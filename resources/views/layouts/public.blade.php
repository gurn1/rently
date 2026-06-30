@php use Illuminate\Support\Facades\Storage; @endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('site_name', 'Rently') ) — Properties to Rent</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <figure class="h-10 overflow-hidden">@include('partials.logo')</figure>

            <nav class="flex items-center gap-6 text-sm">
                <a href="{{ route('properties.index') }}" 
                   class="text-gray-600 hover:text-indigo-600 transition">
                   Properties
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                       My Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="text-gray-600 hover:text-indigo-600 transition">
                       Login
                    </a>
                    @if (Route::has('register') && setting('enable_frontend_registration'))
                        <a href="{{ route('register') }}" 
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                        Register
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @include('partials.alert')
        @yield('content')
    </main>

    <footer class="bg-white shadow-sm mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex justify-between items-center text-sm text-gray-500">
            <span>&copy; {{ date('Y') }} {{ setting('site_name', 'Rently') }}. All rights reserved.</span>
            <nav class="flex gap-6">
                @if(setting('support_phone'))
                    <span>{{ setting('support_phone') }}</span>
                @endif
                @if(setting('contact_email'))
                    <a href="mailto:{{ setting('contact_email') }}" class="hover:text-indigo-600 transition">{{ setting('contact_email') }}</a>
                @endif
                <a href="#" class="hover:text-indigo-600 transition">Privacy Policy</a>
                <a href="#" class="hover:text-indigo-600 transition">Terms</a>
                <a href="#" class="hover:text-indigo-600 transition">Contact</a>
            </nav>
        </div>
    </footer>

</body>
</html>