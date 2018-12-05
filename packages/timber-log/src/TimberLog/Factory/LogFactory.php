<?php

namespace TimberLog\Factory;

use TimberLog\Log\TestLog;
use TimberLog\Log\SimpleLog;
use TimberLog\Log\ReflectionLog;


class LogFactory
{
    public static function createSimple($level, $message) : SimpleLog
    {
        $log = new SimpleLog($level, $message);
        return $log;
    }

    public static function createReflection($level, $message, $r_method) : ReflectionLog
    {
        $log = new ReflectionLog($level, $message, $r_method);
        return $log;
    }
}
