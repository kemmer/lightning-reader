<?php

namespace LightningReader\Parser;


class Tokenizer
{
    /**
     * unit: Smallest text unit possible (character)
     */
    public function compare($unit, $lookFor)
    {
        if(!is_array($lookFor))
            $lookFor = [$lookFor];

        foreach($lookFor as $lf) {
            if($this->compareSingle($unit, $lf))
                return true;
        }

        return false;
    }

    public function compareSingle($unit, $lookFor)
    {
        return ($unit === $lookFor);
    }

    public function bundle($stream, $lookFor = null, bool $alwaysStopEOL = true)
    {
        $result = "";

        $blockSize = strlen($stream);
        for($position = 0; $position < $blockSize; $position++) {
            $unit = $stream[$position];

            if($this->compare($unit, $lookFor) || ($alwaysStopEOL && $this->compareSingle($unit, PHP_EOL)))
                break;

            $result .= $unit;
        }

        return $result;
    }
}
