<?php

namespace TimberLog\Logger;

use TimberLog\Log\LogInterface;
use DateTime;


/**
 * Logger
 */
abstract class Logger implements LoggerInterface
{
    /**
     * Formatting flags
     */
    private $withTimestamp = true;  /* Enables a timestamped output */
    private $withLevel = true;      /* Enables showing the log level information */
    private $withNewLine = true;    /* Enables newline addition at the end of log message */

    public function enableTimestamp(bool $answer)
    {
        $this->withTimestamp = $answer;
    }

    public function enableLevel(bool $answer)
    {
        $this->withLevel = $answer;
    }

    public function enableNewLine(bool $answer)
    {
        $this->withNewLine = $answer;
    }

    /**
     * Produces a formatted timedate string refering to current time
     * using DateTime standard class
     */
    private function timestamp() : string
    {
        $now = new DateTime();
        return $now->format('Y-m-d H:i:s');
    }

    /**
     * Produces a formatted message with desired options
     */
    final protected function release(LogInterface $log) : string
    {
        $format = " %s";
        $args = [$log->message()];

        if($this->withLevel) {
            $format = "[%s]".$format;
            array_unshift($args, $log->level());
        }

        if($this->withTimestamp) {
            $format = "[%s]".$format;
            array_unshift($args, $this->timestamp());
        }

        if($this->withNewLine) {
            $format .= "\n";
        }

        return vsprintf(ltrim($format), $args);
    }
}
