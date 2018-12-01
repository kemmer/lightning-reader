<?php

namespace TimberLog\Logger;

use TimberLog\Log\LogInterface;
use DateTime;


/**
 * Logger
 */
abstract class Logger implements LoggerInterface
{
    /* Enables a timestamped output */
    private $withTimestamp = false;

    /* Enables showing the log level information */
    private $withLevel = false;

    /* Enables newline addition at the end of log message */
    private $withNewLine = false;

    public function setTimestamp(bool $answer)
    {
        $this->withTimestamp = $answer;
    }

    public function setLevel(bool $answer)
    {
        $this->withLevel = $answer;
    }

    public function setNewLine(bool $answer)
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

        return vsprintf($format, $args);
    }
}
