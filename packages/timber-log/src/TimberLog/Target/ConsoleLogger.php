<?php

namespace TimberLog\Target;
use TimberLog\Logger\{LoggerAbstract, LoggerInterface};
use TimberLog\Log\ReflectionLog;

/**
 * ConsoleLogger
 *
 * Responsible for producing log output to the console
 */
class ConsoleLogger extends LoggerAbstract implements LoggerInterface
{
    private function console($type, $log)
    {
        printf("%s %s", $type, $log->message());

        if($log instanceof ReflectionLog)
            printf(" --> %s", $log->context());

        printf("\n");
    }

    public function error($log)
    {
        self::console(self::ERROR_STR, $log);
    }

    public function warning($log)
    {
        self::console(self::WARNING_STR, $log);
    }

    public function info($log)
    {
        self::console(self::INFO_STR, $log);
    }
}
