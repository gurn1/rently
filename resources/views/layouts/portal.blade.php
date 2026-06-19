@php 
    use Illuminate\Support\Facades\Storage;
    
    $notifications = auth()->user()->unreadNotifications->take(5);
    $unreadCount = auth()->user()->unreadNotifications->count();

    $rolePrefix = match(auth()->user()->getRoleNames()->first()) {
        'property_manager' => 'manager',
        'admin' => 'admin',
        'tenant' => 'tenant',
        default => 'tenant'
    };
    
    $profile = auth()->user()->profile;
@endphp
<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Portal') — {{ setting('site_name', 'Rently') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Material+Symbols+Outlined" rel="stylesheet">
    </head>

    <body class="bg-gray-100 text-gray-900 antialiased min-h-screen">

        <main class="flex h-full">
            <aside class="primary-navigation w-sm bg-dark px-8 pt-10 min-h-screen">
                <div class="mb-8 w-[120px]">
                    @include('partials.logo', ['path' => route($rolePrefix . '.dashboard')])
                </div>
                <nav class="text-base flex flex-col sticky top-10 z-50">
                    @include('dashboard.partials.navigation-items')
                </nav>
            </aside>

            <div class="content-container w-full">
                <header class="primary-header bg-white shadow-sm sticky top-0 z-50">
                    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-end items-center">
                        <div class="flex items-center gap-4 text-sm">
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
                                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-md border-gray-100 border z-50">
                                    <div class="px-4 pt-3 pb-1 flex justify-between items-center">
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

                                    <div class="flex flex-col gap-2 my-2 px-3">

                                    @forelse($notifications as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                        class="block px-4 py-3 bg-gray-100 hover:bg-gray-50 rounded-lg transition">
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

                            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full capitalize">
                                {{ str_replace('_', ' ', auth()->user()->getRoleNames()->first()) }}
                            </span>

                            {{-- Profile image or initials --}}
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