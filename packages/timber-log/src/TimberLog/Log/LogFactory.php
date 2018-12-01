<?php

namespace TimberLog\Log;

use TimberLog\Log\SimpleLog;
use TimberLog\Log\ReflectionLog;


class LogFactory
{
    public static function createSimple($message) : SimpleLog
    {
        $log = new SimpleLog($message);
        return $log;
    }

    public static function createReflection($message, $r_method) : ReflectionLog
    {
        $log = new ReflectionLog($message, $r_method);
        return $log;
    }
}
