<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    // Removed ShouldQueue to save immediately instead of queuing
    // use Queueable;

    public $title;
    public $message;
    public $url;
    public $type;

    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $message
     * @param string|null $url
     * @param string $type (info, success, warning, error)
     */
    public function __construct($title, $message, $url = null, $type = 'info')
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url ?? url('/dashboard');
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject($this->title)
                    ->line($this->message)
                    ->action('View Details', $this->url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'type' => $this->type,
        ];
    }
}
