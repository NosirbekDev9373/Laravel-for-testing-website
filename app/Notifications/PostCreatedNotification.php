<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $postTitle;
    public int $postId;
    public function __construct(string $postTitle, int $postId)
    {
        $this->postTitle = $postTitle;
        $this->postId = $postId;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Post muvaffaqiyatli yaratildi!')
            ->greeting('Salom, ' . $notifiable->name . '!')
            ->line('"' . $this->postTitle . '" nomli post yaratildi.')
            ->action('Postni ko\'rish', url('/posts/' . $this->postId))
            ->line('Rahmat!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'post_title' => $this->postTitle,
            'post_id'    => $this->postId,
            'message'    => '"' . $this->postTitle . '" nomli post yaratildi.',
        ];
    }
}
