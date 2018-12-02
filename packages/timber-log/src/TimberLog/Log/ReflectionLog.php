<?php

namespace TimberLog\Log;

use ReflectionMethod;

/**
 * ReflectionLog
 *
 * Log output with message + method reflection details
 */
class ReflectionLog extends Log
{
    private $r_method;
    private $message;

    public function __construct($level, string $message, ReflectionMethod $r_method)
    {
        parent::__construct($level);

        $this->message = $message;
        $this->r_method = $r_method;
    }

    public function message() : string
    {
        $format = "%s (classname: %s | methodname: %s | lines: %s-%s)";
        return sprintf($format,
            $this->message,
            $this->r_method->getDeclaringClass()->getName(),
            $this->r_method->getName(),
            $this->r_method->getStartLine(),
            $this->r_method->getEndLine()
        );
    }
}
