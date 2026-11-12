<?php

namespace Bushuev\Mattermost\Notifications;

use Bushuev\Mattermost\Formatters\NotificationFormatter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MattermostNotification extends Notification
{
    use Queueable;

    protected string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mattermost'];
    }

    /**
     * Get the mattermost representation of the notification.
     *
     * @return NotificationFormatter
     */
    public function toMattermost()
    {
        return new NotificationFormatter(
            enviroment: config('app.env'),
            service: config('app.name'),
            message: $this->message,
        );
    }
}
