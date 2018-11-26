<?php

namespace TimberLog\Logger;

/**
 * LoggerInterface
 */
interface LoggerInterface
{
    public function error($message);
    public function warning($message);
    public function info($message);
}
