<?php

namespace Bushuev\Mattermost;

use Bushuev\Mattermost\Formatters\FormatterInterface;
use Illuminate\Support\Facades\Http;

class MattermostHook
{
    protected ?string $token = null;

    public function handle(FormatterInterface $formatter)
    {
        Http::acceptJson()
            ->contentType('application/json')
            ->post($this->getUrl(), [
                'text' => $formatter->toString(),
            ]);
    }

    protected function getUrl(): string
    {
        $token = empty($this->token)
            ? config('mattermost.token')
            : $this->token;
        return config('mattermost.url') . '/hooks/' . $token;
    }

    public function setToken(string $token): MattermostHook
    {
        $this->token = $token;
        return $this;
    }
}
