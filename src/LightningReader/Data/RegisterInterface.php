<?php

namespace LightningReader\Data;

/**
 * RegisterInterface
 *
 * A register should validate and
 * be able to be sanitized
 *
 * When we validate, we are just making
 * sure that data was like expected
 *
 * When we sanitize, we are transforming
 * the data to some pattern
 */
interface RegisterInterface
{
    public function validate() : bool;  /* Validates all fields */
    public function sanitize() : bool;  /* Sanitizes all fields */
}
