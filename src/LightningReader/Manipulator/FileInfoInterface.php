<?php

namespace LightningReader\Manipulator;


interface FileInfoInterface
{
    public function stream();
    public function lineTracker();
    public function filePath();
}
