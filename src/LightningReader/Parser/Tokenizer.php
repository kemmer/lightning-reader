<?php

namespace LightningReader\Parser;

use LightningReader\Parser\Comparator;
use LightningReader\Parser\Template;


class Tokenizer
{
    public function swallow(&$stream, $stopOn = null, bool $alwaysStopOnEOL = true) : string
    {
        $result = "";

        // Use strict comparison for fgetc() as some input (like '0')
        // could be implicitly casted to false
        while( ($unit = fgetc($stream)) !== false)
        {
            if(Comparator::compare($unit, $stopOn)
                || ($alwaysStopOnEOL && Comparator::compareSingle($unit, PHP_EOL)))
                break;

            $result .= $unit;
        }

        return $result;
    }

    public function bundle(&$stream, Template $template) : string
    {
        // Advance until reaches the beginning of our
        // token to be bundled
        if(!is_null($template->start))
            $this->swallow($stream, $template->start);

        // Now that we reached the beginning of it
        // we will expand the search to get the rest
        return $this->swallow($stream, $template->end);
    }
}
