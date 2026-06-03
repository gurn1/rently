@extends('layouts.portal')

@section('title', 'Users')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users</h1>
            <p class="text-gray-500 mt-1">{{ $users->total() }} users</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
            + New User
        </a>
    </div>

    <div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-900">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </span>
                            </div>
                        </td>
                        <td class="text-gray-600">{{ $user->email }}</td>
                        <td>
                            <span class="text-xs px-2 py-1 rounded-full capitalize
                                {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-700' :
                                   ($user->hasRole('property_manager') ? 'bg-blue-100 text-blue-700' :
                                   'bg-green-100 text-green-700') }}">
                                {{ str_replace('_', ' ', $user->roles->first()?->name ?? 'No role') }}
                            </span>
                        </td>
                        <td class="text-gray-600">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-indigo-600 hover:underline">View</a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="text-indigo-600 hover:underline">Edit</a>
                                @if($user->id !== auth()->id())
                                    <form method="POST"
                                          action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection