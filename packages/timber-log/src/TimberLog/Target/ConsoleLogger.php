<?php

namespace TimberLog\Target;

use TimberLog\Logger\Logger;
use TimberLog\Log\LogInterface;

/**
 * ConsoleLogger
 *
 * Responsible for producing log output to the console
 */
class ConsoleLogger extends Logger
{
    private function console($level, LogInterface $log)
    {
        printf("%s\n", $this->release($level, $log));
    }

    public function error(LogInterface $log)
    {
        self::console(self::ERROR_STR, $log);
    }

    public function warning(LogInterface $log)
    {
        self::console(self::WARNING_STR, $log);
    }

    public function info(LogInterface $log)
    {
        self::console(self::INFO_STR, $log);
    }
}
