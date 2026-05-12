@extends('layouts.portal')

@section('title', 'Conversation with ' . $conversation->propertyManager->first_name)

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="mb-6">
            <a href="{{ route('tenant.messages.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to messages
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">
                {{ $conversation->propertyManager->first_name }} {{ $conversation->propertyManager->last_name }}
            </h1>
        </div>

        {{-- Message thread --}}
        <div class="bg-white rounded-lg shadow p-6 mb-4 space-y-4 max-h-[60vh] overflow-y-auto" id="message-thread">
            @forelse($conversation->messages as $message)
                @if($message->is_system_message)
                    <div class="text-center">
                        <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full">
                            🔔 {{ $message->body }}
                        </span>
                    </div>
                @elseif($message->sender_id === auth()->id())
                    <div class="flex justify-end">
                        <div class="max-w-sm">
                            <div class="bg-indigo-600 text-white px-4 py-2 rounded-lg rounded-tr-none text-sm">
                                {{ $message->body }}
                            </div>
                            <p class="text-xs text-gray-400 mt-1 text-right">
                                {{ $message->created_at->diffForHumans() }}
                                @if($message->read_at)
                                    · Read
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <div class="flex justify-start">
                        <div class="max-w-sm">
                            <div class="bg-gray-100 text-gray-900 px-4 py-2 rounded-lg rounded-tl-none text-sm">
                                {{ $message->body }}
                            </div>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $message->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endif
            @empty
                <p class="text-center text-gray-400 text-sm">No messages yet.</p>
            @endforelse
        </div>

        {{-- Reply form --}}
        <form method="POST" action="{{ route('tenant.messages.store', $conversation) }}">
            @csrf
            <div class="bg-white rounded-lg shadow p-4 flex gap-3">
                <textarea name="body"
                          rows="2"
                          placeholder="Type a message..."
                          class="flex-1 border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm resize-none">{{ old('body') }}</textarea>
                <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition text-sm self-end">
                    Send
                </button>
            </div>
            @error('body')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>
    </div>

    <script>
        const thread = document.getElementById('message-thread');
        thread.scrollTop = thread.scrollHeight;
    </script>
@endsection