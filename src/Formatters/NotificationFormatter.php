<?php

namespace Bushuev\Mattermost\Formatters;

use Throwable;

class NotificationFormatter implements FormatterInterface
{
    public function __construct(
        public string $enviroment,
        public string $service,
        public string $message,
    )
    {
    }

    public static function fromException(Throwable $exception): self
    {
        return new self(
            enviroment: config('app.env'),
            service: config('app.name'),
            message: $exception->getMessage(),
        );
    }

    public function toArray(): array
    {
        return [
            'enviroment' => $this->enviroment,
            'service' => $this->service,
            'message' => $this->message,
        ];
    }

    public function toString(): string
    {
        $message = '**enviroment**: `' . $this->enviroment . '`' . "\n";
        $message .= '**service**: `' . $this->service . '`' . "\n";

        $message .= '**message**: `' . $this->message . '`'. "\n\n";

        return $message;
    }
}
