<?php

namespace LightningReader\Data;

/**
 * SerializeInterface
 *
 * An entity that is meant to be transmitted or
 * inserted in the database should implement
 * this interface
 */
interface SerializeInterface
{
    public function toArray() : array;  /* Transforms our data into an associative array */
}
