<?php

namespace Bushuev\Mattermost\Tests;

use Bushuev\Mattermost\Formatters\DefaultFormatter;
use Bushuev\Mattermost\MattermostHook;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function test_formatter_and_hook_integration()
    {
        $formatter = new DefaultFormatter(
            enviroment: 'testing',
            service: 'test-service',
            message: 'Test message',
            request: null,
            isConsole: true,
            trace: []
        );

        $this->assertIsString($formatter->toString());
        $this->assertStringContainsString('**enviroment**: `testing`', $formatter->toString());

        // Check that the hook can be instantiated
        $hook = new MattermostHook();
        $this->assertInstanceOf(MattermostHook::class, $hook);
    }
}
