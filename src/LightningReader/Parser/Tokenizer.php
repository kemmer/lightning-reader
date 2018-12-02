<?php

namespace LightningReader\Parser;


class Tokenizer
{
    /**
     * unit: Smallest text unit possible (character)
     * block: One or more units arranged in a sequence (string)
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

    public function bundle($block, $lookFor = null, bool $alwaysStopEOL = true)
    {
        $result = "";

        $blockSize = strlen($block);
        for($position = 0; $position < $blockSize; $position++) {
            $unit = $block[$position];

            if($this->compare($unit, $lookFor) || ($alwaysStopEOL && $this->compareSingle($unit, PHP_EOL)))
                break;

            $result .= $unit;
        }

        return $result;
    }
}
