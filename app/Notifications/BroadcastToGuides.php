<?php
 namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class BroadcastToGuides extends Notification implements ShouldQueue
{
    use Queueable;
    public string $title;
    public string $body;
    public function __construct(string $title, string $body)
    {
        $this->title = $title;
        $this->body  = $body;
    }

    public function via($notifiable): array
    {
        // عدّل القنوات كما تريد (mail, database, broadcast …)
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => $this->title,
            'body'  => $this->body,
        ]);
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'body'  => $this->body,
        ]);
    }

    // لو أردت قناة mail:
    // public function toMail($notifiable) { ... }
}
