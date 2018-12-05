<?php

namespace LightningReader\Parser;


class Template
{
    public $start;  /* Opening delimiter for field */
    public $end;    /* Closing delimiter for field */

    /**
     * Example:
     *
     * *DATAHERE`
     * The '*' will be the $start and '`' the $end
     */

    public function __construct($start, string $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
}
