<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $rolePrefix = auth()->user()->routePrefix();
        $data = $notification->data;

        return match($data['type']) {
            'new_message'          => redirect()->route($rolePrefix . '.messages.show', $data['conversation_id']),
            'work_order_created',
            'work_order_updated',
            'work_order_comment'   => redirect()->route($rolePrefix . '.work-orders.show', $data['work_order_id']),
            'document_uploaded',
            'document_signed'      => redirect()->route($rolePrefix . '.documents.show', $data['document_id']),
            'lease_status_changed' => redirect()->route($rolePrefix . '.leases.show', $data['lease_id']),
            default                => redirect()->route('dashboard'),
        };
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}