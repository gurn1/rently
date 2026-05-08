<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal') — Rently</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 antialiased">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                    Rently
                </a>
                <div class="hidden md:flex items-center gap-6 text-sm">
                    @role('tenant')
                        <a href="{{ route('tenant.dashboard') }}" class="text-gray-600 hover:text-indigo-600 transition">Dashboard</a>
                    @endrole
                    @role('property_manager')
                        <a href="{{ route('manager.dashboard') }}" class="text-gray-600 hover:text-indigo-600 transition">Dashboard</a>
                        <a href="#" class="text-gray-600 hover:text-indigo-600 transition">Properties</a>
                        <a href="#" class="text-gray-600 hover:text-indigo-600 transition">Tenants</a>
                    @endrole
                    @role('admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-indigo-600 transition">Dashboard</a>
                        <a href="#" class="text-gray-600 hover:text-indigo-600 transition">Properties</a>
                        <a href="#" class="text-gray-600 hover:text-indigo-600 transition">Users</a>
                    @endrole
                </div>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-500">
                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                </span>
                <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full capitalize">
                    {{ auth()->user()->getRoleNames()->first() }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="text-red-500 hover:text-red-700 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @include('partials.alert')
        @yield('content')
    </div>

</body>
</html>