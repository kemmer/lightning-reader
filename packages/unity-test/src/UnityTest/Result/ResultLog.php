<?php

namespace UnityTest\Result;

use TimberLog\Log\Log;
use TimberLog\Log\LogLevel;

/**
 * ResultLog
 *
 * Log output specializing for testing featuring:
 * - Default log level at TEST
 */
class ResultLog extends Log
{
    private $result;
    private $verbose;

    public function __construct(ResultInterface $result, bool $verbose)
    {
        parent::__construct(LogLevel::TEST_STR);

        $this->result = $result;
        $this->verbose = $verbose;
    }

    public function message() : string
    {
        /**
         * The $verbose flag will activate the verbose mode, a more expressive
         * way of seeing tests
         * The message in verbose mode is more expressive and wordy, and a
         * contextual information will also be shown in case of unsuccessful tests
         *
         * When not in verbose mode, only a
         */
        if($this->verbose)
            return $this->verboseMessage();
        else
            return $this->silentMessage();
    }

    /**
     * Creates a message for verbose mode
     * The message in verbose mode is more expressive and wordy, and a
     * contextual information will also be shown in case of unsuccessful tests
     */
    private function verboseMessage() : string
    {
        if($this->result->wasSuccess()) {
            return "------";

        } else {
            $contextInformation = sprintf(
                " (classname: %s | methodname: %s | lines: %s-%s)",
                $this->result->method()->getDeclaringClass()->getName(),
                $this->result->method()->getName(),
                $this->result->method()->getStartLine(),
                $this->result->method()->getEndLine());

            if($this->result->wasFail())
                return "FAILED ASSERTION ".$this->result->exception()->getAssertName().$contextInformation;

            elseif($this->result->wasError())
                return "ERROR".$contextInformation;
        }
    }

    /**
     * Creates a message in non-verbose mode
     * This will only show dots in case of successful tests and
     * other letters in case of unsuccessful tests
     */
    private function silentMessage() : string
    {
        if($this->result->wasSuccess()) {
            return ".";

        } elseif($this->result->wasFail()) {
            return "F";

        } elseif($this->result->wasError()) {
            return "E";
        }
    }
}

