<?php

namespace UnityTest\Assert;

use UnityTest\Assert\AssertDescription;
use UnityTest\Assert\AssertException;

/**
 * AssertTrait
 *
 * Provides testing/assertion capabilities inside a class
 * - Assertion methods always begin with 'assert'
 * - The returned value is 0 if result was the expected one for that test
 * - Otherwise, an AssertException is thrown
 * - We're not interested in return values
 * - Members must be protected (we don't want anybody from outside using unless they extend/use the trait)
 */
trait AssertTrait
{
    protected function assertTrue(bool $expression) : int
    {
        if($expression !== true)
            throw new AssertException(AssertDescription::TRUE);

        return 0;
    }

    protected function assertFalse(bool $expression) : int
    {
        if($expression !== false)
            throw new AssertException(AssertDescription::FALSE);

        return 0;
    }

    protected function assertEquals($one, $two) : int
    {
        if($one != $two)
            throw new AssertException(AssertDescription::EQUALS);

        return 0;
    }
}
