<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $postTitle) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Postingiz o\'chirildi')
            ->greeting('Salom, '.$notifiable->name.'!')
            ->line('Sizning "'.$this->postTitle.'" nomli postingiz o\'chirildi.')
            ->line('Agar bu xato bo\'lsa, biz bilan bog\'laning.')
            ->action('Saytga o\'tish', url('/'))
            ->line('Rahmat!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'post_title' => $this->postTitle,
            'message' => '"'.$this->postTitle.'" postingiz o\'chirildi.',
        ];
    }
}
