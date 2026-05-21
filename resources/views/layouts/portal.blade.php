@php use Illuminate\Support\Facades\Storage; @endphp

{{-- Notification bell --}}
@php
    $notifications = auth()->user()->unreadNotifications->take(5);
    $unreadCount = auth()->user()->unreadNotifications->count();

    $rolePrefix = match(auth()->user()->getRoleNames()->first()) {
        'property_manager' => 'manager',
        'admin' => 'admin',
        'tenant' => 'tenant',
        default => 'tenant'
    };
@endphp
<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Portal') — {{ setting('site_name', 'Rently') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>

    <body class="bg-gray-100 text-gray-900 antialiased min-h-screen">

        <main class="flex h-full">
            <aside class="primary-navigation w-md bg-dark px-12 pt-8 min-h-screen">
                <div class="mb-10">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-highlight">
                        {{ setting('site_name', 'Rently') }}
                    </a>
                </div>
                <nav class="text-base flex flex-col">
                    <div class="flex flex-col">
                        <span>Main</span>
                        @role('tenant')
                            <a href="{{ route('tenant.dashboard') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('tenant.dashboard') ? 'text-indigo-600 font-medium' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('tenant.leases.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('tenant.leases.*') ? 'text-indigo-600 font-medium' : '' }}">
                                My Lease
                            </a>
                            <a href="{{ route('tenant.payments.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('tenant.payments.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Payments
                            </a>
                            <a href="{{ route('tenant.documents.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('tenant.documents.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Documents
                            </a>
                            <a href="{{ route('tenant.work-orders.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('tenant.work-orders.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Work Orders
                            </a>
                            <a href="{{ route('tenant.messages.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('tenant.messages.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Messages
                            </a>
                        @endrole

                        @role('property_manager')
                            <a href="{{ route('manager.dashboard') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('manager.dashboard') ? 'text-indigo-600 font-medium' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('manager.properties.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('manager.properties.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Properties
                            </a>
                            <a href="{{ route('manager.leases.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('manager.leases.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Leases
                            </a>
                            <a href="{{ route('manager.payments.index') }}"
                                class="text-white hover:text-indigo-600 transition {{ request()->routeIs('manager.payments.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Payments
                            </a>
                            <a href="{{ route('manager.documents.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('manager.documents.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Documents
                            </a>
                            <a href="{{ route('manager.work-orders.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('manager.work-orders.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Work Orders
                            </a>
                            <a href="{{ route('manager.messages.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('manager.messages.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Messages
                            </a>
                        @endrole

                        @role('admin')
                            <a href="{{ route('admin.dashboard') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 font-medium' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.properties.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('admin.properties.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Properties
                            </a>
                            <a href="{{ route('admin.leases.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('admin.leases.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Leases
                            </a>
                            <a href="{{ route('admin.payments.index') }}"
                                class="text-white hover:text-indigo-600 transition {{ request()->routeIs('admin.payments.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Payments
                            </a>
                            <a href="{{ route('admin.documents.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('admin.documents.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Documents
                            </a>
                            <a href="{{ route('admin.work-orders.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('admin.work-orders.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Work Orders
                            </a>
                            <a href="{{ route('admin.settings.index') }}"
                            class="text-white hover:text-indigo-600 transition {{ request()->routeIs('admin.payments.*') ? 'text-indigo-600 font-medium' : '' }}">
                                Settings
                            </a>
                        @endrole
                    </div>

                    <div class="flex flex-col mt-12">
                        <span>You</span>
                        <a href="{{ route($rolePrefix . '.profile.edit') }}" 
                        class="text-white hover:text-indigo-600 transition {{ request()->routeIs('tenant.messages.*') ? 'text-indigo-600 font-medium' : '' }}">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-white transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            <div class="content-container w-full">
                <header class="primary-header bg-white shadow-sm sticky top-0 z-50">
                    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-end items-center">
                        <div class="flex items-center gap-4 text-sm">

                            {{-- Profile image or initials --}}
                            @php $profile = auth()->user()->profile; @endphp
                            <a href="{{ route($rolePrefix . '.profile.edit') }}" class="flex items-center gap-2 hover:opacity-80 transition">
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

                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="relative text-gray-500 hover:text-indigo-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    @endif
                                </button>

                                <div x-show="open"
                                    @click.away="open = false"
                                    x-transition
                                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border z-50">
                                    <div class="px-4 py-3 border-b flex justify-between items-center">
                                        <p class="font-semibold text-sm text-gray-700">Notifications</p>
                                        @if($unreadCount > 0)
                                            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                                @csrf
                                                <button type="submit" class="text-xs text-indigo-600 hover:underline">
                                                    Mark all read
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @forelse($notifications as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                        class="block px-4 py-3 border-b last:border-0 hover:bg-gray-50 transition">
                                            <p class="text-sm text-gray-800">{{ $notification->data['message'] }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </a>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-400 text-sm">
                                            No new notifications
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="p-12">
                    @include('partials.alert')
                    @yield('content')
                </div>
            </div>
        </main>
    </body>
</html>