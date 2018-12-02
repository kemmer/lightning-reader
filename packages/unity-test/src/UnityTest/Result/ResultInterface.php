<?php

namespace UnityTest\Result;

use ReflectionMethod;
use Exception;


interface ResultInterface
{
    public function wasSuccess() : bool;
    public function wasFail() : bool;
    public function wasError() : bool;

    public function method() : ReflectionMethod;
    public function exception() : Exception;
}
