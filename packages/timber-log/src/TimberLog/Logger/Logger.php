<?php

namespace TimberLog\Logger;

use TimberLog\Log\LogInterface;
use DateTime;


/**
 * LoggerAbstract
 */
abstract class Logger implements LoggerInterface
{
    /* Levels */
    const ERROR_STR   = "ERROR";
    const WARNING_STR = "WARNING";
    const INFO_STR    = "INFO";

    private function timestamp()
    {
        $now = new DateTime();
        return $now->format('Y-m-d H:i:s');
    }

    protected function release($level, LogInterface $log) : string
    {
        $format = "[%s] [%s] %s";
        return sprintf($format, $this->timestamp(), $level, $log->message());
    }
}
