<?php


namespace Language\Logger;


interface ILogger
{
    public function info(string $message, array $context = []): void;

    public function warning(string $message, array $context = []): void;

    public function error($message, array $context = []): void;
}