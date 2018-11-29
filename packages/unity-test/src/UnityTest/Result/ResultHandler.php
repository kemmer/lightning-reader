<?php

namespace UnityTest\Result;

use UnityTest\Test\Result;
use ReflectionMethod;


class ResultHandler
{
    /* This flag will tell results to provide a more expressive and detailed result output */
    private bool $verbose;

    public function __construct(bool $verbose = false)
    {
        $this->verbose = $verbose;
    }

    public function createSuccess(ReflectionMethod $rm) : Result
    {
        $result = new Result(true, $rm);
        $result->setVerbose($this->verbose);

        return $result;
    }

    public function createFailure(ReflectionMethod $rm, $e) : Result
    {
        $result = new Result(false, $rm, $e);
        $result->setVerbose($this->verbose);

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
