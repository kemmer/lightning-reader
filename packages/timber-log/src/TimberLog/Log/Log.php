<?php

namespace TimberLog\Log;

use TimberLog\Log\LogLevel;

/**
 * Log
 *
 * What a log should minimally do
 */
abstract class Log implements LogInterface
{
    protected $message;
    protected $level;

    public function __construct($level, string $message)
    {
        $this->level = $level;
        $this->message = $message;
    }

    final public function level() : string
    {
        return $this->level;
    }

    public function message() : string
    {
        return $this->message;
    }
}
