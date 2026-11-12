<?php

namespace Bushuev\Mattermost\Formatters;

use Illuminate\Http\Request;
use Throwable;

class DefaultFormatter implements FormatterInterface
{
    public function __construct(
        public string $enviroment,
        public string $service,
        public string $message,
        public ?Request $request,
        public bool $isConsole = false,
        public ?array $trace,
    )
    {
    }

    public static function fromException(Throwable $exception): self
    {
        return new self(
            enviroment: config('app.env'),
            service: config('app.name'),
            message: $exception->getMessage(),
            request: !app()->runningInConsole() ? request() : null,
            isConsole: app()->runningInConsole(),
            trace: array_filter($exception->getTrace(), fn ($trace) => !preg_match('/^(illuminate|symfony).*/im', $trace['class'])),
        );
    }

    public function toArray(): array
    {
        return [
            'enviroment' => $this->enviroment,
            'service' => $this->service,
            'message' => $this->message,
            'request' => $this->request,
            'trace' => $this->trace,
        ];
    }

    public function toString(): string
    {
        $message = '**enviroment**: `' . $this->enviroment . '`' . "\n";
        $message .= '**service**: `' . $this->service . '`' . "\n";
        if ($this->isConsole) {
            $message .= '**request**: `console execution`' . "\n";
        } else if ($this->request) {
            $message .= '**request**' . "\n";
            $message .= '    ip: `' . $this->request->getClientIp() . '`' . "\n";
            $message .= '  host: `' . $this->request->getHost() . '`' . "\n";
            $message .= '   url: `' . $this->request->getBaseUrl() . '`' . "\n";
            $message .= '  path: `' . $this->request->path() . '`' . "\n";
        }

        $message .= '**message**: `' . $this->message . '`'. "\n\n";

        if (count($this->trace ?? [])) {
            $message .= '**trace**' . "\n" . '```JSON' . json_encode($this->trace, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . '```';
        }

        return $message;
    }
}
