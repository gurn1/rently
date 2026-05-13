@php use Illuminate\Support\Facades\Storage; @endphp

@extends('layouts.portal')

@section('title', 'My Profile')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-500 mt-1">Manage your personal information.</p>
        </div>

        <form method="POST"
              action="{{ route(auth()->user()->getRoleNames()->first() . '.profile.update') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Avatar --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Profile Photo</h2>
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
                        <input type="file" name="profile_image" accept="image/*"
                               class="text-sm text-gray-600">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG or WEBP. Max 2MB.</p>
                        @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Account details --}}
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2">Account Details</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Personal details --}}
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2">Personal Details</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Legal Name</label>
                        <input type="text" name="legal_name" value="{{ old('legal_name', $profile->legal_name) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('legal_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Name</label>
                        <input type="text" name="preferred_name" value="{{ old('preferred_name', $profile->preferred_name) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('preferred_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Home Address</label>
                        <input type="text" name="address" value="{{ old('address', $profile->address) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Emergency contact --}}
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2">Emergency Contact</h2>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="emergency_contact_name"
                               value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('emergency_contact_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="emergency_contact_phone"
                               value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('emergency_contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                        <input type="text" name="emergency_contact_relationship"
                               value="{{ old('emergency_contact_relationship', $profile->emergency_contact_relationship) }}"
                               placeholder="e.g. Spouse, Parent"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('emergency_contact_relationship') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Save Changes
                </button>
            </div>
        </form>

        {{-- Password change section --}}
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Change Password</h2>
            <a href="{{ route('password.request') }}"
               class="text-sm text-indigo-600 hover:underline">
                Reset your password via email
            </a>
        </div>
    </div>
@endsection