<?php

namespace TimberLog\Log;

/**
 * SimpleLog
 *
 * Log output with a simple message
 */
class SimpleLog extends Log
{
    private $message;

    public function __construct($level, $message)
    {
        parent::__construct($level);

        $this->message = $message;
    }

    public function message() : string
    {
        return $this->message;
    }
}
