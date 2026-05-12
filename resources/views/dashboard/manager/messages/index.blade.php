@extends('layouts.portal')

@section('title', 'Messages')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
        <p class="text-gray-500 mt-1">Your conversations with tenants.</p>
    </div>

    @if($conversations->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No conversations yet.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @foreach($conversations as $conversation)
                @php
                    $lastMessage = $conversation->messages->first();
                    $unread = $conversation->messages()
                        ->whereNull('read_at')
                        ->where('sender_id', '!=', auth()->id())
                        ->count();
                @endphp
                <a href="{{ route('manager.messages.show', $conversation) }}"
                   class="flex items-center gap-4 px-6 py-4 border-b last:border-0 hover:bg-gray-50 transition">

                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold text-sm flex-shrink-0">
                        {{ strtoupper(substr($conversation->tenant->first_name, 0, 1)) }}{{ strtoupper(substr($conversation->tenant->last_name, 0, 1)) }}
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <p class="font-medium text-gray-900">
                                {{ $conversation->tenant->first_name }} {{ $conversation->tenant->last_name }}
                            </p>
                            @if($conversation->last_message_at)
                                <p class="text-xs text-gray-400 flex-shrink-0 ml-4">
                                    {{ $conversation->last_message_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                        @if($lastMessage)
                            <p class="text-sm text-gray-500 truncate">
                                {{ $lastMessage->is_system_message ? '🔔 ' : '' }}{{ $lastMessage->body }}
                            </p>
                        @endif
                    </div>

                    {{-- Unread badge --}}
                    @if($unread > 0)
                        <span class="bg-indigo-600 text-white text-xs rounded-full px-2 py-0.5 flex-shrink-0">
                            {{ $unread }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $conversations->links() }}
        </div>
    @endif
@endsection