<?php

namespace Bushuev\Mattermost\Tests\Notifications;

use Bushuev\Mattermost\Formatters\DefaultFormatter;
use Bushuev\Mattermost\MattermostHook;
use Bushuev\Mattermost\Notifications\MattermostChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

class MattermostChannelTest extends TestCase
{
    public function test_send_notification()
    {
        Http::fake();

        $mattermostHook = new MattermostHook();
        $channel = new MattermostChannel($mattermostHook);

        $notification = new class extends Notification {
            public function via($notifiable)
            {
                return ['mattermost'];
            }

            public function toMattermost()
            {
                return new DefaultFormatter(
                    enviroment: 'testing',
                    service: 'test-service',
                    message: 'Test message',
                    request: null,
                    isConsole: true,
                    trace: []
                );
            }
        };

        $notifiable = new class {};

        $channel->send($notifiable, $notification);

        Http::assertSent(function ($request) {
            return $request['text'] === '**enviroment**: `testing`' . "\n" .
                   '**service**: `test-service`' . "\n" .
                   '**request**: `console execution`' . "\n" .
                   '**message**: `Test message`'. "\n\n";
        });
    }
}
