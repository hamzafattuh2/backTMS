<?php
// app/Notifications/PriceSuggested.php
namespace App\Notifications;

use App\Models\TripPriceSuggestion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PriceSuggested extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TripPriceSuggestion $suggestion   // PHP‑8 promoted prop
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];   // أو ['database','broadcast'] لو كنت تستعمل Pusher
    }

    public function toArray($notifiable): array
    {
        $guide = $this->suggestion->guide;   // علاقة belongsTo في المودِل
        return [
            'suggestion_id' => $this->suggestion->id,
            'price'         => $this->suggestion->price,
            'guide' => [
                'id'         => $guide->id,
                'full_name'  => $guide->user->first_name . ' ' . $guide->user->last_name,
                'languages'  => $guide->languages,
                'years_exp'  => $guide->years_of_experience,
            ],
        ];
    }

    // مثال لبريد بسيط
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('اقتراح سعر جديد لرحلتك')
            ->greeting('مرحباً،')
            ->line('قام أحد المرشدين باقتراح سعر جديد لرحلتك.')
            ->action('عرض الاقتراح', url("/trips/{$this->suggestion->trip_id}/suggestions"))
            ->line('شكراً لاستخدامك منصتنا!');
    }
}
