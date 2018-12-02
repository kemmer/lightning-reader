<?php

namespace TimberLog\Target;

use TimberLog\Logger\Logger;
use TimberLog\Log\LogInterface;

/**
 * ConsoleLogger
 *
 * Produces log output to the console
 */
class ConsoleLogger extends Logger
{
    public function output(LogInterface $log)
    {
        printf($this->release($log));
    }

    public function finish()
    {
        printf("\n");
    }
}
