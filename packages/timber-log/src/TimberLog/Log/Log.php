<?php

namespace TimberLog\Log;

class Log
{
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function message()
    {
        return sprintf("%s", $this->message);
    }
}
