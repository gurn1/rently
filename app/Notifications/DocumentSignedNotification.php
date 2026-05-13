<?php
namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentSignedNotification extends Notification
{
    use Queueable;

    public function __construct(public Document $document) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'     => $this->document->tenant->first_name . ' has signed "' . $this->document->title . '".',
            'document_id' => $this->document->id,
            'signed_at'   => $this->document->signed_at,
            'type'        => 'document_signed',
        ];
    }
}