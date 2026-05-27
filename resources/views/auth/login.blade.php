<x-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h1 class="text-3xl text-center font-semibold mb-9">Login to your account</h1>

    @if (Route::has('register'))
        <div class="size-sm text-center mb-4">
            Don't have an account? 
            <a class="underline mt-3 text-sm text-teal font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                {{ __('Create an account') }}
            </a>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4 flex items-center justify-end">
            <label for="remember_me" class="inline-flex items-center mr-2">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="label pl-1.5">{{ __('Remember me') }}</span>
            </label>

            <x-primary-button class="">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="mt-4 mb-2 text-right">
            @if (Route::has('password.request'))
                <a class="underline text-right mt-3 text-sm text-teal font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
        @endif
        </div>
    </form>
</x-auth-layout>
