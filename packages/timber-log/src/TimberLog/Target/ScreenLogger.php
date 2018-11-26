<?php

namespace TimberLog\Target;
use TimberLog\Logger\{LoggerAbstract, LoggerInterface};
use TimberLog\Log\ReflectionLog;


class ScreenLogger extends LoggerAbstract implements LoggerInterface
{
    private function screen($type, $log)
    {
        printf("%s %s", $type, $log->message());

        if($log instanceof ReflectionLog)
            printf(" --> %s", $log->context());

        printf("\n");
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
