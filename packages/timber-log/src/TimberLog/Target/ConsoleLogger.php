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
    public function __construct(bool $rough = false)
    {
        /**
         * $rough parameter means that output should not be modified in any way
         * and should obey exclusively the default Logger base class definitions
         */
        if(!$rough) {
            $this->setTimestamp(true);
            $this->setLevel(true);
            $this->setNewLine(true);
        }
    }

    public function output(LogInterface $log)
    {
        printf($this->release($log));
    }
}
