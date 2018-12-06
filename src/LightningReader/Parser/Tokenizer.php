<?php

namespace LightningReader\Parser;

use LightningReader\Parser\Comparator;
use LightningReader\Parser\Template;
use LightningReader\Manipulator\FileInfoInterface;

/**
 * Tokenizer
 *
 * Indented to perform parsing analysis over some stream (file, STDIN)
 * and return specific information
 */
class Tokenizer
{
    private $file;               /* The holds the resource to be observed. The input state is controlled by it */
    private $auditBuffer;        /* A custom buffer holding text content read. Must be handled manually when enabled */
    private $auditBufferEnabled; /* Indicates that auditBuffer is enabled and registering properly */

    public function __construct(FileInfoInterface $file, bool $enableAuditBuffer = false)
    {
        $this->file = $file;
        $this->auditBuffer = "";
        $this->auditBufferEnabled = $enableAuditBuffer;
    }

    /**
     * Audit a $unit, storing it in our buffer
     */
    private function audit(string $unit)
    {
        if($this->auditBufferEnabled) {
            $this->auditBuffer .= $unit;
        }
    }

    /**
     * Clear audit buffer, so it won't be filled with repeated data
     * or using unecessary memory
     *
     * It is usually used when we finish swallowing some segment of
     * content of our interest (e.g. a line from a file)
     */
    public function clearAuditBuffer()
    {
        $this->auditBuffer = "";
    }

    /**
     * This will allow us to inspect the data inside audit buffer
     * The reasons for that may vary
     *
     * Note that if not $auditBufferEnabled, this will always
     * return the empty string
     */
    public function getAuditBuffer() : string
    {
        return $this->auditBuffer;
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
        while( ($unit = fgetc($this->file->stream())) !== false)
        {
            // Anything read from the file will be able to be audited
            $this->audit($unit);

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
