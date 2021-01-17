<?php


namespace Language;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MonologAdapter implements ILogger
{
    private Logger $logger;

    /**
     * MonologAdapter constructor.
     */
    public function __construct()
    {
        $this->logger = new Logger('logger');
        $this->logger->pushHandler(new StreamHandler('php://stdout'));
    }

    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }
}