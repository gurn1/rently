@php use Illuminate\Support\Facades\Storage; @endphp
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
                        <a href="{{ route('tenant.dashboard') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('tenant.dashboard') ? 'text-indigo-600 font-medium' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('tenant.leases.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('tenant.leases.*') ? 'text-indigo-600 font-medium' : '' }}">
                            My Lease
                        </a>
                        <a href="{{ route('tenant.documents.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('tenant.documents.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Documents
                        </a>
                        <a href="{{ route('tenant.work-orders.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('tenant.work-orders.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Work Orders
                        </a>
                        <a href="{{ route('tenant.messages.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('tenant.messages.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Messages
                        </a>
                    @endrole

                    @role('property_manager')
                        <a href="{{ route('manager.dashboard') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('manager.dashboard') ? 'text-indigo-600 font-medium' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('manager.properties.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('manager.properties.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Properties
                        </a>
                        <a href="{{ route('manager.leases.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('manager.leases.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Leases
                        </a>
                        <a href="{{ route('manager.documents.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('manager.documents.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Documents
                        </a>
                        <a href="{{ route('manager.work-orders.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('manager.work-orders.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Work Orders
                        </a>
                        <a href="{{ route('manager.messages.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('manager.messages.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Messages
                        </a>
                    @endrole

                    @role('admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 font-medium' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.properties.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('admin.properties.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Properties
                        </a>
                        <a href="{{ route('admin.leases.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('admin.leases.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Leases
                        </a>
                        <a href="{{ route('admin.documents.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('admin.documents.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Documents
                        </a>
                        <a href="{{ route('admin.work-orders.index') }}"
                           class="text-gray-600 hover:text-indigo-600 transition {{ request()->routeIs('admin.work-orders.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Work Orders
                        </a>
                    @endrole

                </div>
            </div>

            <div class="flex items-center gap-4 text-sm">

                {{-- Profile image or initials --}}
                @php $profile = auth()->user()->profile; @endphp
                <a href="{{ route(auth()->user()->getRoleNames()->first() . '.profile.edit') }}"
                   class="flex items-center gap-2 hover:opacity-80 transition">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        @if($profile?->profile_image)
                            <img src="{{ Storage::url($profile->profile_image) }}"
                                 alt="{{ auth()->user()->first_name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-xs font-bold text-indigo-600">
                                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <span class="text-gray-500 hidden md:block">
                        {{ auth()->user()->first_name }}
                    </span>
                </a>

                <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full capitalize">
                    {{ str_replace('_', ' ', auth()->user()->getRoleNames()->first()) }}
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