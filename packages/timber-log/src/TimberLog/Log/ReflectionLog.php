<?php

namespace TimberLog\Log;

/**
 * ReflectionLog
 *
 * A specialized unit in showing log contexts based
 * on reflection
 */
class ReflectionLog extends Log
{
    private $r_method;

    public function __construct(string $message, \ReflectionMethod $r_method)
    {
        parent::__construct($message);

        $this->r_method = $r_method;
    }

    public function context() : string
    {
        return sprintf("(classname: %s | methodname: %s | lines: %s-%s)",
            $this->r_method->getDeclaringClass()->getName(),
            $this->r_method->getName(),
            $this->r_method->getStartLine(),
            $this->r_method->getEndLine()
        );
    }
}
