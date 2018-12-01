<?php

namespace TimberLog\Logger;

use TimberLog\Log\LogInterface;

/**
 * LoggerInterface
 */
interface LoggerInterface
{
    public function setTimestamp(bool $answer);
    public function setLevel(bool $answer);
    public function setNewLine(bool $answer);

    public function output(LogInterface $log);
}
