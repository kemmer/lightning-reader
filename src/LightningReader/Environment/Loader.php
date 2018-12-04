<?php

namespace LightningReader\Environment;

use LightningReader\Environment\Context;


class Loader
{
    public static function load(string $path = __DIR__."/../../../.env")
    {
        $stream = @fopen($path, "r");

        /**
         * In case of errors, create an empty context
         */
        if($stream === false)
            return false;

        $environment = [];

        while(($keyPair = fgets($stream)) !== false) {
            $var = explode("=", $keyPair);

            if(!isset($var[1]))
                $var[1] = "";   /* It is not the responsibility here to know if this is a valid input*/

            $environment[$var[0]] = trim($var[1]);
        }

        return new Context($environment);
    }
}
