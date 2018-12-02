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
            if($this->compareUnit($unit, $lf))
                return true;
        }

        return false;
    }

    public function compareUnit($unit, $lookFor)
    {
        return ($unit === $lookFor);
    }

    public function readBlock($stream)
    {
        $result = "";

        $blockSize = strlen($stream);
        for($position = 0; $position < $blockSize; $position++) {
            $current = $stream[$position];

            if($current == PHP_EOL)
                break;

            $result .= $current;
        }

        return $result;
    }
}
