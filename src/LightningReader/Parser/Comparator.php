<?php

namespace LightningReader\Parser;


class Comparator
{
    /**
     * unit: Smallest text unit possible (character)
     * block: One or more units arranged in a sequence (string)
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
