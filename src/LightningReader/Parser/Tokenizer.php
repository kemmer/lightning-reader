<?php

namespace LightningReader\Parser;

use LightningReader\Parser\Comparator;


class Tokenizer
{

    public function bundle(string $block, $lookFor = null, bool $alwaysStopEOL = true) : string
    {
        $result = "";

        $blockSize = strlen($block);
        for($position = 0; $position < $blockSize; $position++) {
            $unit = $block[$position];

            if($this->compare($unit, $lookFor) || ($alwaysStopEOL && $this->compareSingle($unit, PHP_EOL)))
            if(Comparator::compare($unit, $stopOn)
                || ($alwaysStopOnEOL && Comparator::compareSingle($unit, PHP_EOL)))
                break;

            $result .= $unit;
        }

        return $result;
    }
}
