<?php

namespace TimberLog\Logger;

use TimberLog\Log\LogInterface;

/**
 * LoggerInterface
 */
interface LoggerInterface
{
    public function error(LogInterface $log);
    public function warning(LogInterface $log);
    public function info(LogInterface $log);
}
