<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends Notification
{
    use Queueable;
    protected array $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New User Registered',
            'message' => 'A new user has registered.',
            'user' => [
                'id' => $this->user['id'],
                'name' => $this->user['name'],
                'email' => $this->user['email'],
            ],
        ];
    }
}
