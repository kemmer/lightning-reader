<?php

namespace TimberLog\Target;
use TimberLog\Logger\{LoggerAbstract, LoggerInterface};


class ScreenLogger extends LoggerAbstract implements LoggerInterface
{
    private function screen($type, $log)
    {
        printf("%s %s --> %s\n", $type, $log->message(), $log->context());
    }

    public function error($log)
    {
        self::screen(self::ERROR_STR, $log);
    }

    public function warning($log)
    {
        self::screen(self::WARNING_STR, $log);
    }

    public function info($log)
    {
        self::screen(self::INFO_STR, $log);
    }
}
