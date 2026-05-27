<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="h-screen flex sm:justify-center items-center">

            <div class="flex mx-auto h-screen w-screen">
                <div class="bg-gray-100 min-h-full w-[40%] p-7">
                    <div class="max-w-[120px]">
                        <a href="/">
                            @include('partials.logo')
                        </a>
                    </div>
                </div>

                <div class="bg-white shadow-lg w-[60%] flex items-center justify-center px-10">
                    <div class="max-w-[480px] w-full">
                        {{ $slot }}
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
