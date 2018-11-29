<?php

namespace UnityTest\Assert;

use AssertCode;
use AssertException;

/**
 * AssertTrait
 *
 * Provides testing/assertion capabilities inside a class
 * - Assertion methods always begin with 'assert'
 * - The returned value is 0 if result was the expected one for that test
 * - Otherwise, an AssertException is thrown
 * - Members must be protected (we don't want anybody from outside using unless they extend)
 */
trait AssertTrait
{
    protected function assertTrue(bool $expression) : int
    {
        if($expression !== true)
            throw AssertException(AsserCode::TRUE);

        return 0;
    }

    protected function assertEquals($one, $two) : int
    {
        if($one != $two)
            throw  AssertException(AssertCode::EQUALS);

        return 0;
    }
}
