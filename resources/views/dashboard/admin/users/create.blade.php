@extends('layouts.portal')

@section('title', 'Create User')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Create New User</h1>
            <a href="{{ route('admin.users.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to users
            </a>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 space-y-6">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Create User
                </button>
            </div>
        </form>
    </div>
@endsection