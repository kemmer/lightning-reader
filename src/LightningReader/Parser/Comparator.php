<?php

namespace LightningReader\Parser;

/**
 * Comparator
 *
 * Simply compare strings in a safe way
 *
 * unit: Smallest text unit possible (character)
 * block: One or more units arranged in a sequence (string)
 *
 */
class Comparator
{
    /**
     * Compare one or multiple strings
     * If array is passed in $lookFor, we will compare multiple times
     * and return the first positive (true) result found, or else false
     *
     * If you intend to use just one lookFor, it is more performant
     * to use compareSimple() if your logic allows
     *
     * @param  string        $unit      The unit
     * @param  string|array  $lookFor   What should we look for (in the unit)
     * @return bool                     Comparison result between unit and look for
     */
    public static function compare(string $unit, $lookFor) : bool
    {
        if(is_null($lookFor) || strlen($unit) === 0)
            return false;

        if(!is_array($lookFor))
            $lookFor = [$lookFor];

        foreach($lookFor as $lf) {
            if(self::compareSingle($unit, $lf))
                return true;
        }

        return false;
    }

    /**
     * Compare
     *
     * @param  string $unit     The unit
     * @param  string $lookFor  What should we look for (in the unit)
     * @return bool             Comparison result between unit and look for
     */
    public static function compareSingle(string $unit, string $lookFor) : bool
    {
        /**
         * This comparison is usually faster than using strcmp()
         * and its good as we are just interested in boolean values,
         * not about the size of comparisons
         */
        return ($unit === $lookFor);
    }
}
