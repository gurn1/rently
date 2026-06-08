@extends('layouts.portal')

@section('title', 'Edit ' . $user->first_name)

@section('content')
    <div class="mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
            <a href="{{ route('admin.users.show', $user) }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to user
            </a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="panel">

                <div class="grid grid-cols-2 gap-4 input-container">
                    <div>
                        <x-input-label>First Name</x-input-label>
                        <x-text-input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"/>
                        @error('first_name') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label>Last Name</x-input-label>
                        <x-text-input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"/>
                        @error('last_name') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="input-container">
                    <x-input-label>Email Address</x-input-label>
                    <x-text-input type="email" name="email" value="{{ old('email', $user->email) }}"/>
                    @error('email') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label>Role</x-input-label>
                    <x-select 
                        name="role"
                        placeholder="Select a role"
                        :selected="old('role')"
                        :options="$roles->pluck('name')->mapWithKeys(fn($name) => [$name => ucwords(str_replace('_', ' ', $name))])->toArray()"
                    />
                    @error('role') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label>
                            New Password
                            <span class="text-gray-400 font-normal">(leave blank to keep current)</span>
                        </x-input-label>
                        <x-text-input type="password" name="password"/>
                        @error('password') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label>Confirm Password</x-input-label>
                        <x-text-input type="password" name="password_confirmation"/>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <x-outline-button href="{{  route('admin.users.show', $user) }}">
                    Cancel
                </x-outline-button>
                <x-primary-button>
                    Save Changes
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection