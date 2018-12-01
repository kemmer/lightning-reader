<?php

namespace TimberLog\Log;

use TimberLog\Log\PlainLog;
use TimberLog\Log\ReflectionLog;


class LogFactory
{
    public static function createPlain($message) : PlainLog
    {
        $log = new PlainLog($message);
        return $log;
    }

    public static function createReflection($message, $r_method) : ReflectionLog
    {
        $log = new ReflectionLog($message, $r_method);
        return $log;
    }
}
