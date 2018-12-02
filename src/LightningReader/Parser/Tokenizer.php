<?php

namespace LightningReader\Parser;


class Tokenizer
{
    /**
     * unit: Smallest text unit possible (character)
     * block: One or more units arranged in a sequence (string)
     */
    public function compare(string $unit, $lookFor) : bool
    {
        if(is_null($lookFor) || strlen($unit) === 0)
            return false;

        if(!is_array($lookFor))
            $lookFor = [$lookFor];

        foreach($lookFor as $lf) {
            if($this->compareSingle($unit, $lf))
                return true;
        }

        return false;
    }

    public function compareSingle(string $unit, string $lookFor) : bool
    {
        /**
         * This comparison is usually faster than using strcmp()
         * and its good as we are just interested in boolean values,
         * not about the size of comparisons
         */
        return ($unit === $lookFor);
    }

    public function bundle(string $block, $lookFor = null, bool $alwaysStopEOL = true) : string
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
