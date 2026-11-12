<?php

namespace Bushuev\Mattermost\Notifications;

use Bushuev\Mattermost\MattermostHook;
use Illuminate\Notifications\Notification;

class MattermostChannel
{
    protected MattermostHook $mattermostHook;

    public function __construct(MattermostHook $mattermostHook)
    {
        $this->mattermostHook = $mattermostHook;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $routeNotificationForMattermost = config('mattermost.token');

        // Get the formatter from the notification or use default
        if (method_exists($notification, 'routeNotificationForMattermost')) {
            $routeNotificationForMattermost = $notifiable->routeNotificationForMattermost();
        }

        // Get the formatter from the notification or use default
        if (method_exists($notification, 'toMattermost')) {
            $formatter = $notification->toMattermost($notifiable);
        } else {
            // If there's no specific formatter method, we'll use a default approach
            $formatter = new \Bushuev\Mattermost\Formatters\DefaultFormatter(
                enviroment: config('app.env'),
                service: config('app.name'),
                message: $message['message'] ?? __('No message provided'),
                request: !app()->runningInConsole() ? request() : null,
                isConsole: app()->runningInConsole(),
                trace: null
            );
        }

        $this->mattermostHook
            ->setToken($routeNotificationForMattermost)
            ->handle($formatter);
    }
}
