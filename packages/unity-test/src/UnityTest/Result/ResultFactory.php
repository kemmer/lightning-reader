<?php

namespace UnityTest\Result;

use UnityTest\Result\Result;
use ReflectionMethod;
use ErrorException;


class ResultFactory
{
    public static function createSuccess(ReflectionMethod $rm) : Result
    {
        $result = new Result(true, $rm);
        return $result;
    }

    public static function createFailure(ReflectionMethod $rm, $e) : Result
    {
        $result = new Result(false, $rm, $e);
        return $result;
    }

    public static function convertErrorToException($severity, $message, $file, $line)
    {
        if (!(error_reporting() & $severity)) {
            // This error code is not included in error_reporting
            return;
        }

        throw new ErrorException($message, 0, $severity, $file, $line);
    }
}
