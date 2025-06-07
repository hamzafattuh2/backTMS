<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewArticleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $article;

    public function __construct($article)
    {
        $this->article = $article;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // قنوات الإرسال
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'مقال جديد تم إنشاؤه: ' . $this->article->title,
            'link' => route('articles.show', $this->article->id)
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('إشعار بمقال جديد')
                    ->line('تم إنشاء مقال جديد بواسطة ' . $notifiable->name)
                    ->action('عرض المقال', url('/articles/'.$this->article->id))
                    ->line('شكراً لاستخدامك نظامنا!');
    }
}
