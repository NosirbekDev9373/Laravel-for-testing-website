<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $postTitle;
    public int $postId;
    public string $commenterName;
    public string $commentBody;
    public function __construct(
        string $postTitle,
        int $postId,
        string $commenterName,
        string $commentBody
    )
    {
        $this->postTitle     = $postTitle;
        $this->postId        = $postId;
        $this->commenterName = $commenterName;
        $this->commentBody   = $commentBody;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                ->subject('Postingizga yangi izoh!')
                ->greeting('Salom, ' . $notifiable->name . '!')
                ->line('"' . $this->postTitle . '" postingizga ' . $this->commenterName . ' izoh qoldirdi.')
                ->line('Izoh: ' . $this->commentBody)
                ->action('Postni ko\'rish', url('/posts/' . $this->postId))
                ->line('Rahmat!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'post_title'     => $this->postTitle,
            'post_id'        => $this->postId,
            'commenter_name' => $this->commenterName,
            'comment_body'   => $this->commentBody,
            'message'        => $this->commenterName . ' postingizga izoh qoldirdi.',
        ];
    }
}
