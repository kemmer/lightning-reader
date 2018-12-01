<?php

namespace UnityTest\Result;

use ReflectionMethod;
use UnityTest\Assert\AssertException;


class Result
{
    /* Whether the test result is success of failure */
    private $status;

    /* Reflected 'test_' method responsible for this result */
    private $rMethod;

    /* Exception generated, in case of failures only */
    private $exception;

    public function __construct(bool $s, ReflectionMethod $rm, $e = null)
    {
        $this->status = $s;
        $this->rMethod = $rm;
        $this->exception = $e;
    }

    private function outputQuiet() : string
    {
        $outputContent = "";

        /* This will simply output a minimal indication for a success, assert failure or error */
        if($this->status == true)
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

    public function output(bool $verbose = false) : string
    {
        if(!$verbose)
            return $this->outputQuiet();
        else
            return $this->outputVerbose();
    }
}
