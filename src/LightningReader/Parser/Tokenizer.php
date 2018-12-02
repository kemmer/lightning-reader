<?php

namespace LightningReader\Parser;


class Tokenizer
{
    /**
     * unit: Smallest text unit possible (character)
     */
    public function compareEOL($unit)
    {
        if($unit === PHP_EOL)
            return true;

        return false;
    }

    public function compare($unit, $compare)
    {
        if($unit === $compare)
            return true;

        return false;
    }


    public function readBlock($stream, $separator = null)
    {
        $result = "";

        $blockSize = strlen($stream);
        for($position = 0; $position < $blockSize; $position++) {
            $current = $stream[$position];

            if($current == PHP_EOL || $current == $separator)
                break;

            $result .= $current;
        }

        return $result;
    }
}
