<?php

namespace UnityTest\Result;

use ReflectionMethod;
use UnityTest\Assert\AssertException;


class Result
{
    /* Whether the test result is success of failure */
    private bool $status;

    /* Reflected 'test_' method responsible for this result */
    private ReflectionMethod $rMethod;

    /* Exception generated, in case of failures only */
    private $exception;

    /* This flag will tell output() to provide a more expressive and detailed result output */
    private bool $verbose;

    public function __construct(bool $s, ReflectionMethod $rm, $e = null)
    {
        $this->verbose = false;

        $this->status = $s;
        $this->rMethod = $rm;
        $this->exception = $e;
    }

    public function setVerbose(bool $v)
    {
        $this->verbose = $v;
    }

    private function outputQuiet() : string
    {
        $outputContent = "";

        /* This will simply output a minimal indication for a success, assert failure or error */
        if($status == true)
            $outputContent = '.';
        elseif($this->exception instanceof AssertException)
            $outputContent = 'F';
        else
            $outputContent = 'E';

        return $outputContent;
    }

    private function outputVerbose() : string
    {
        // ...
    }

    public function output() : string
    {
        if(!$this->verbose)
            return $this->outputQuiet();
        else
            return $this->outputVerbose();
    }
}
