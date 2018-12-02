<?php

namespace LightningReader\Parser;

use LightningReader\Parser\Comparator;


class Tokenizer
{
    public function bundle(string $block, $stopOn = null, bool $alwaysStopOnEOL = true) : string
    {
        $result = "";

        $blockSize = strlen($block);
        for($position = 0; $position < $blockSize; $position++) {
            $unit = $block[$position];

            if(Comparator::compare($unit, $stopOn)
                || ($alwaysStopOnEOL && Comparator::compareSingle($unit, PHP_EOL)))
                break;

            $result .= $unit;
        }

        return $result;
    }
}
