<?php

namespace LightningReader\Validator\Rule;


/**
 * Helpers
 *
 * Some static methods for helping enforcing the rules
 */
class Helpers
{
    /**
     * Test for simple rules specified by single chars at $rule
     * @param  string $unit     The unit to be tested
     * @param  string $rule     Rule specification
     * @return bool             Returns true the testing was successful
     */
    private static function checkByMaskRule(string $unit, string $rule) : bool
    {
        if($rule === "n")
            return is_numeric($unit);
        elseif($rule === "a")
            return !is_numeric($unit);
        else
            return ($unit === $rule);
    }

    /**
     * Checks using a mask defined by a string
     * @param  string $data     The data to be checked
     * @param  string $mask     The specified mask
     * @return bool             Returns true if the mask pattern matches with $data
     */
    public static function checkByMask(string $data, string $mask) : bool
    {
        // A mask should match exacly in length out data to be checked
        if(strlen($data) != strlen($mask))
            return false;

        for($position = 0; $position < strlen($mask); $position++)
        {
            $rule = $mask[$position];
            if(! self::checkByMaskRule($data[$position], $mask[$position]))
                return false;
        }

        return true;
    }
}
