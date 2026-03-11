<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Message $message
    ) {}

    /**
     * Deliver via mail + database so users see a bell-notification in the UI.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $chatUrl = url('/user/chats/' . $this->message->chat_id);

        return (new MailMessage)
            ->subject('New message in your chat')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('You have received a new message:')
            ->line('"' . \Str::limit($this->message->body, 100) . '"')
            ->action('View Chat', $chatUrl)
            ->line('Thank you for using Chat Reply System!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'chat_id'    => $this->message->chat_id,
            'preview'    => \Str::limit($this->message->body, 80),
            'from'       => $this->message->user?->name ?? 'System',
        ];
    }
}
