<?php


namespace Language;


interface ILogger
{
    public function info(string $message, array $context = []): void;
}