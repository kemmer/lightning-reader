<?php

namespace LightningReader\Manipulator;

use LightningReader\Manipulator\LineTracker;
use LightningReader\Manipulator\FileInfoInterface;

/**
 * FileInfo
 *
 * Holds the file stream and some information
 * about the file
 */
class FileInfo implements FileInfoInterface
{
    private $filePath;      /* The path of opened file */
    private $stream;        /* The resource used to interact with file */
    private $lineTracker;   /* For keeping track of line modifications on the file */

    public function __construct(string $filePath = "")
    {
        $this->stream = null;
        $this->lineTracker = null;

        if(!empty($filePath)) {
            $this->openFile($filePath);
            $this->lineTracker = new LineTracker;
        }
    }

    private function openFile($filePath)
    {
        if(file_exists($filePath)) {
            $this->filePath = $filePath;
            $this->stream = fopen($filePath, "r");
        }
    }

    public function stream()
    {
        return $this->stream;
    }

    public function lineTracker()
    {
        return $this->lineTracker;
    }

    public function filePath() : string
    {
        return $this->filePath;
    }
}
