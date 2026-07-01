<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Notifications\NewMessageNotification;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        // Ensure the user belongs to this conversation
        $user = auth()->user();
        $belongsToConversation = $conversation->tenant_id === $user->id
            || $conversation->property_manager_id === $user->id;

        if (!$belongsToConversation) {
            abort(403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $user->id,
            'body'            => $validated['body'],
            'is_system_message' => false,
        ]);

        // Update last_message_at on conversation
        $conversation->update(['last_message_at' => now()]);

        // Notify the other party
        $recipient = auth()->id() === $conversation->tenant_id
            ? $conversation->propertyManager
            : $conversation->tenant;

        $recipient->notify(new NewMessageNotification($message, $conversation));

        // Redirect back to the conversation
        if ($user->hasRole('property_manager')) {
            return redirect()->route('manager.messages.show', $conversation)
                ->with('success', 'Message sent.');
        }

        return redirect()->route('tenant.messages.show', $conversation)
            ->with('success', 'Message sent.');
    }
}
