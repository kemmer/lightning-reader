<?php

namespace UnityTest\Result;

use UnityTest\Result\Result;
use ReflectionMethod;
use ErrorException;


/**
 * ResultFactory
 *
 * Used to create specific results for each use case
 */
class ResultFactory
{
    /**
     * Creates a successful result
     * A successful test must represent a situation when the
     * method being tested executed normally
     */
    public static function createSuccess(ReflectionMethod $rm) : Result
    {
        $result = new Result(true, $rm);
        return $result;
    }

    /**
     * Creates a unsuccessful test
     * An unsuccessful test must represent a situation when
     * some unexpected behavior (represented by an exception)
     * occurred
     */
    public static function createFailure(ReflectionMethod $rm, $e) : Result
    {
        $result = new Result(false, $rm, $e);
        return $result;
    }

    /**
     * When set to the appropriated handler, this will catch
     * some errors and throw an exception instead of leaving the
     * control to the default interpreter's action
     *
     * Useful when we wanna show fancy messages to the user when
     * this situation occur instead of letting the big stack trace
     * polute the output
     *
     * Registered with set_error_handler()
     */
    public static function convertErrorToException($severity, $message, $file, $line)
    {
        /**
         * error_reporting() returns the code define in error_reporting configuration
         * This comparison avoids throwing exceptions for things that are
         * not defined as being errors and are undesired to be treated here
         */
        if (!(error_reporting() & $severity)) {
            // This error code is not included in error_reporting
            return;
        }

        /**
         * ErrorException is defined in core PHP and
         */
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
}
