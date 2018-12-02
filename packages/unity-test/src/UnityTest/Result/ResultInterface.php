<?php

namespace UnityTest\Result;

use ReflectionMethod;
use Exception;

/**
 * ResultInterface
 */
interface ResultInterface
{
    public function wasSuccess() : bool;            /* true if resulted as expected */
    public function wasFail() : bool;               /* true if resulted in an assertion failure */
    public function wasError() : bool;              /* true if resulted in error */

    public function method() : ReflectionMethod;    /* Returns the reflected method  that produced the result */
    public function exception() : Exception;        /* Returns the exception generated by the result */
}
