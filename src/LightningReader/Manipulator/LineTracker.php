<?php

namespace LightningReader\Manipulator;

/**
 * LineTracker
 *
 * Keeps track of our position inside the input file
 * Accumulates success and errors statistics
 */
class LineTracker
{
    private $lineCurrent       = 0;
    private $lineSuccess_Count = 0;
    private $lineError_Count   = 0;

    public function current() : int
    {
        return $this->lineCurrent;
    }

    public function advance()
    {
        $this->lineCurrent++;
    }

    public function newSuccess(int $quantity = 1)
    {
        $this->lineSuccess_Count += $quantity;
    }

    public function newError()
    {
        $this->lineError_Count++;
    }
}
