<?php

namespace LightningReader\Data;

/**
 * CompletenessInterface
 *
 * Any entity looking for check and present its completeness
 * (some state where it can be considered useful) must implement
 * this interface
 */
interface CompletenessInterface
{
    public function complete() : bool;  /* Tells whether is ready to use */
}
