<?php

namespace LightningReader\Parser;

use LightningReader\Parser\Comparator;
use LightningReader\Parser\Template;

/**
 * Tokenizer
 *
 * Indented to perform parsing analysis over some stream (file, STDIN)
 * and return specific information
 */
class Tokenizer
{
    private $stream;    /* The resource to be observed. The input state is controlled by it */

    public function __construct(&$stream)
    {
        $this->stream = $stream;
    }

    /**
     * Reads the stream resource input until reaches some objective
     * Objective may be an specific unit or simply the end of line
     *
     * Can be used to simply forward the $stream to the desired place,
     * simply by defining the targes with $stopOn and $alwaysStopOnEOL
     * and ignoring the output
     *
     * @param  string|null  $stopOn             The objective
     * @param  bool         $alwaysStopOnEOL    If true, stops at EOL if found before $stopOn
     * @return string                           The input accumulated
     */
    public function swallow($stopOn = null, bool $alwaysStopOnEOL = true) : string
    {
        $result = "";

        // Use strict comparison for fgetc() as some input (like '0')
        // could be implicitly casted to false
        while( ($unit = fgetc($this->stream)) !== false)
        {
            if(Comparator::compare($unit, $stopOn)
                || ($alwaysStopOnEOL && Comparator::compareSingle($unit, PHP_EOL)))
                break;

            $result .= $unit;
        }

        return $result;
    }

    /**
     * Parses $template and defines start and end delimiters,
     * forwards the $stream to reach the starting place then
     * reads until the end, bundling everything between start and end
     *
     * This results in the so-called TOKEN
     *
     * @param  Template $template Definition for delimiters
     * @return string             The bundled string (TOKEN)
     */
    public function bundle(Template $template) : string
    {
        // Advance until reaches the beginning of our
        // token to be bundled
        if(!is_null($template->start))
            $this->swallow($template->start);

        // Now that we reached the beginning of it
        // we will expand the search to get the rest
        return $this->swallow($template->end);
    }
}
