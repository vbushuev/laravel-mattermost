# Laravel Mattermost Package

This package provides a way to send notifications to Mattermost via webhooks.

## Installation

Add package directory 
```bash
/packages/vbushuev/laravel-mattermost
```
Add config to `composer.json` to `autoload` section
```json
"autoload": {
        "psr-4": {
            ...
            "Bushuev\\Mattermost\\": "packages/vbushuev/laravel-mattermost/src"
        }
    }
```


Publish the config file:
```bash
php artisan vendor:publish --provider="Bushuev\Mattermost\Providers\ServiceProvider"
```

Configure your Mattermost webhook URL and token in `.env`:
```env
MATTERMOST_URL=https://chat.service.it-pvk.ru/hooks/ywaj4kqfit8edkggiuayr5iyro
MATTERMOST_TOKEN=ywaj4kqfit8edkggiuayr5iyro
```

## Usage

### Send Notification via Mattermost Channel

Add the channel to your notifiable model:
```php
// In a User model or other notifiable class
public function routeNotificationForMattermost($notification)
{
    // Return the webhook URL or null to use default config
    return env('MATTERMOST_URL');
}
```

### Send a Notification

You can now send notifications through Mattermost:

```php
// Using the notification channel directly
use Bushuev\Mattermost\Notifications\MattermostNotification;

// In your controller, command, etc.
$notifiable->notify(new MattermostNotification('Hello from Laravel!'));

// Or send it directly using the service container
app('mattermost')->handle($formatter);
```

### Creating Custom Notifications

Create a custom notification class:
```php
use Illuminate\Notifications\Notification;
use Bushuev\Mattermost\Formatters\DefaultFormatter;

class MyCustomNotification extends Notification
{
    public function via($notifiable)
    {
        return ['mattermost'];
    }
    
    public function toMattermost()
    {
        return new DefaultFormatter(
            enviroment: config('app.env'),
            service: config('app.name'),
            message: 'Custom message',
            request: !app()->runningInConsole() ? request() : null,
            isConsole: app()->runningInConsole(),
            trace: null
        );
    }
}
```

## Features

- Integration with Laravel's notification system
- Support for different formatters
- Simple webhook configuration
- Console and HTTP request context handling
