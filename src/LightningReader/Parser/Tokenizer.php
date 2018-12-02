<?php

namespace LightningReader\Parser;


class Tokenizer
{
    public function compareNewLine($char)
    {
        if($char === "\n")
            return true;

        return false;
    }

    public function compareEOL($char)
    {
        if($char === PHP_EOL)
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
