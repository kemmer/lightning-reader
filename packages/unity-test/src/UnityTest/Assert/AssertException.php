<?php

namespace UnityTest\Assert;

use Exception;

/**
 * AssertException
 *
 * This indicates some assertion have explicitally failed
 */
class AssertException extends Exception
{
    public $assertCode;

    public function __construct(int $code) {
        // This code indicates specifically which kind of assertion have failed
        $this->assertCode = $code;

        // The assertCode is the only relevant part, leaving the rest with default values
        parent::__construct(null, 0, null);
    }

    public function getAssertCode() : int
    {
        return $this->assertCode;
    }
}
