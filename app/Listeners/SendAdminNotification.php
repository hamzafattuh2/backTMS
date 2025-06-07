<?php
namespace App\Listeners;

use App\Events\ArticleCreated;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdminNotification implements ShouldQueue
{
    public function handle(ArticleCreated $event)
    {
        // جلب جميع المشرفين
        $admins = User::where('role', 'admin')->get();

        // إرسال الإشعار لكل مشرف
        foreach ($admins as $admin) {
            $admin->notify(new NewArticleNotification($event->article));
        }
    }
}
