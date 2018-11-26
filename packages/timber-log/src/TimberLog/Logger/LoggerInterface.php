<?php

namespace TimberLog\Logger;

interface LoggerInterface
{
    public function error($message);
    public function warning($message);
    public function info($message);
}
