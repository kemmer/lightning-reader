<?php

namespace UnityTest\Result;

use ReflectionMethod;
use Exception;
use UnityTest\Assert\AssertException;
use UnityTest\Result\ResultInterface;


class Result implements ResultInterface
{
    /* Whether the test result is success of failure */
    private $status;

    /* Reflected 'test_' method responsible for this result */
    private $rMethod;

    /* Exception generated, in case of failures only */
    private $exception;

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
