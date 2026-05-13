<?php
namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentUploadedNotification extends Notification
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
            'message'           => 'A new document has been shared with you: ' . $this->document->title,
            'document_id'       => $this->document->id,
            'requires_signature' => $this->document->requires_signature,
            'type'              => 'document_uploaded',
        ];
    }
}