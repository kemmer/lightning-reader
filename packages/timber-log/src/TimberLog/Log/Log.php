<?php

namespace TimberLog\Log;

use TimberLog\Log\LogLevel;

/**
 * Log
 *
 * What a log should minimally do
 * Only LogLevel must be specified at minimum
 * Generate message() will be a matter of how concretes implement it
 */
abstract class Log implements LogInterface
{
    protected $level;

    public function __construct($level)
    {
        $this->level = $level;
    }

    final public function level() : string
    {
        return $this->level;
    }
}
