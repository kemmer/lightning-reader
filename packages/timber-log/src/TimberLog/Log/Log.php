<?php

namespace TimberLog\Log;

/**
 * Log
 *
 * What a log should minimally do
 */
abstract class Log implements LogInterface
{
    protected $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function message() : string
    {
        return $this->message;
    }
}
