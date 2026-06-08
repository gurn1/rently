@php 
use Illuminate\Support\Facades\Storage; 

$rolePrefix = match(auth()->user()->getRoleNames()->first()) {
    'property_manager' => 'manager',
    'admin' => 'admin',
    'tenant' => 'tenant',
    default => 'tenant'
};
@endphp

@extends('layouts.portal')

@section('title', 'Profile')

@section('content')
    <div class="">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-500 mt-1">Manage your personal information.</p>
        </div>

        <form method="POST"
              action="{{ route($rolePrefix . '.profile.update') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Avatar --}}
            <div class="panel">
                <h2 class="panel-title">Profile Photo</h2>
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        @if($profile->profile_image)
                            <img src="{{ Storage::url($profile->profile_image) }}"
                                 alt="{{ $user->first_name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-indigo-600">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <x-text-input type="file" name="profile_image" accept="image/*"/>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG or WEBP. Max 2MB.</p>
                        @error('profile_image') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Account details --}}
            <div class="panel">
                <h2 class="panel-title">Account Details</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div class="input-container">
                        <x-input-label class="block text-sm font-medium text-gray-700 mb-1">First Name</x-input-label>
                        <x-text-input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"/>
                        @error('first_name') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div class="input-container">
                        <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Last Name</x-input-label>
                        <x-text-input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"/>
                        @error('last_name') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Email Address</x-input-label>
                    <x-text-input type="email" name="email" value="{{ old('email', $user->email) }}"/>
                    @error('email') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Personal details --}}
            <div class="panel">
                <h2 class="panel-title">Personal Details</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div class="input-container">
                        <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Legal Name</x-input-label>
                        <x-text-input type="text" name="legal_name" value="{{ old('legal_name', $profile->legal_name) }}"/>
                        @error('legal_name') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div class="input-container">
                        <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Preferred Name</x-input-label>
                        <x-text-input type="text" name="preferred_name" value="{{ old('preferred_name', $profile->preferred_name) }}"/>
                        @error('preferred_name') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</x-input-label>
                        <x-text-input type="text" name="phone" value="{{ old('phone', $profile->phone) }}"/>
                        @error('phone') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Home Address</x-input-label>
                        <x-text-input type="text" name="address" value="{{ old('address', $profile->address) }}"/>
                        @error('address') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Emergency contact --}}
            <div class="panel">
                <h2 class="panel-title">Emergency Contact</h2>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Name</x-input-label>
                        <x-text-input type="text" name="emergency_contact_name"
                               value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}"/>
                        @error('emergency_contact_name') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Phone</x-input-label>
                        <x-text-input type="text" name="emergency_contact_phone"
                               value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}"/>
                        @error('emergency_contact_phone') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Relationship</x-input-label>
                        <x-text-input type="text" name="emergency_contact_relationship"
                               value="{{ old('emergency_contact_relationship', $profile->emergency_contact_relationship) }}"
                               placeholder="e.g. Spouse, Parent"/>
                        @error('emergency_contact_relationship') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 mb-5">
                <x-primary-button type="submit">
                    Save Changes
                </x-primary-button>
            </div>
        </form>

        {{-- Password change section --}}
        <div class="panel">
            <h2 class="panel-title">Change Password</h2>
            <a href="{{ route('password.request') }}"
               class="text-sm text-indigo-600 hover:underline">
                Reset your password via email
            </a>
        </div>
    </div>
@endsection