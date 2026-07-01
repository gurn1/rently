<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('property_manager_id', auth()->id())
            ->with(['tenant', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('dashboard.manager.messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        // Ensure the manager owns this conversation
        if ((int) $conversation->property_manager_id !== (int) auth()->id()) {
            abort(403);
        }

        $conversation->load(['tenant', 'messages.sender']);

        // Mark unread messages as read
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        return view('dashboard.manager.messages.show', compact('conversation'));
    }
}
