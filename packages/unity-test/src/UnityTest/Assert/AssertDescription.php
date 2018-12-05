<?php

namespace UnityTest\Assert;

/**
 * AssertCode
 *
 * Just a few constants defining assertions currently defined in the system
 * Must be updated in case new assertions are created
 */
class AssertDescription
{
    const TRUE   = 11;
    const FALSE  = 12;
    const EQUALS = 13;

    public static function nameByCode(int $code) : string
    {
        if($code == self::TRUE)
            return "TRUE";
        if($code == self::FALSE)
            return "FALSE";
        if($code == self::EQUALS)
            return "EQUALS";
    }
}
