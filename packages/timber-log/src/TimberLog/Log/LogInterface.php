<?php

namespace TimberLog\Log;

/**
 * LogInterface
 */
interface LogInterface
{
  public function level() : string;
  public function message() : string;
}
