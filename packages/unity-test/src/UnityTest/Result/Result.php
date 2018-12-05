<?php

namespace UnityTest\Result;

use ReflectionMethod;
use Exception;
use UnityTest\Assert\AssertException;
use UnityTest\Result\ResultInterface;


/**
 * Result
 *
 * Represents the result of a test over a method
 * Agregates all the relevant information for understanding
 * what happened after the test
 */
class Result implements ResultInterface
{
    private $status;    /* Whether the test result is success of failure */
    private $rMethod;   /* Reflected 'test_' method responsible for this result */
    private $exception; /* Exception generated, in case of failures only */

    public function __construct(bool $status, ReflectionMethod $rm, $e = null)
    {
        $this->status = $status;
        $this->rMethod = $rm;
        $this->exception = $e;
    }

    public function wasSuccess() : bool
    {
        return $this->status;
    }

    public function wasFail() : bool
    {
        if($this->status == false && $this->exception instanceof AssertException)
            return true;
        return false;
    }

    public function wasError() : bool
    {
        if($this->status == false && !($this->exception instanceof AssertException))
            return true;
        return false;
    }

    public function method() : ReflectionMethod
    {
        return $this->rMethod;
    }

    public function exception() : Exception
    {
        return $this->exception;
    }
}
