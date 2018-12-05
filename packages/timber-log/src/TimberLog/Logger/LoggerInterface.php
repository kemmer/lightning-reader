<?php

namespace TimberLog\Logger;

use TimberLog\Log\LogInterface;

/**
 * LoggerInterface
 */
interface LoggerInterface
{
    public function enableTimestamp(bool $answer);
    public function enableLevel(bool $answer);
    public function enableNewLine(bool $answer);

    public function output(LogInterface $log);
    public function finish();
}
