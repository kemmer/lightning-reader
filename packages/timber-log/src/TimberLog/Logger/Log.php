<?php

namespace TimberLog\Logger;

class Log
{
    private $method;
    private $message;

    public function __construct($method, string $message)
    {
        $this->method = $method;
        $this->message = $message;
    }

    public function context() : string
    {
        return sprintf("(classname: %s | methodname: %s | lines: %s-%s)",
            $this->method->getDeclaringClass()->getName(),
            $this->method->getName(),
            $this->method->getStartLine(),
            $this->method->getEndLine()
        );
    }

    public function message()
    {
        return sprintf("Message: %s", $this->message);
    }
}
