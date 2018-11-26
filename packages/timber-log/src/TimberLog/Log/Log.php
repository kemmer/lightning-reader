<?php

namespace TimberLog\Log;

/**
 * Log
 *
 * A basic unit of a log
 */
class Log
{
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function message()
    {
        return sprintf("%s", $this->message);
    }
}
