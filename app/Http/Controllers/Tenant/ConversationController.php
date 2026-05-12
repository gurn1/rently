<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('tenant_id', auth()->id())
            ->with(['propertyManager', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('dashboard.tenant.messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation->messages->first() ?? $conversation);

        $conversation->load(['propertyManager', 'messages.sender']);

        // Mark unread messages as read
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        return view('dashboard.tenant.messages.show', compact('conversation'));
    }
}
