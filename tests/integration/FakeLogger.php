<?php

namespace tests\integration;

class FakeLogger implements \Language\Logger\ILogger
{
    public function error($message, array $context = []): void
    {
    }

    public function warning(string $message, array $context = []): void
    {
    }

    public function info(string $message, array $context = []): void
    {
    }
}