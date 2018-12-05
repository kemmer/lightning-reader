<?php

namespace LightningReader\Environment;


/**
 * Context
 *
 * An instance of this class holds ENVIRONMENT parameters
 * like database, manipulator options, timeouts and  other
 * useful stuff
 *
 * This class is intended to be set at startup and never
 * changed during execution
 *
 * Static is not preferred as user may want to use many
 * different context objects for different modules or
 * execution contexts
 */
class Context
{
    private $environment;

    public function __construct(array $loaded = [])
    {
        $this->environment = [];

        foreach($loaded as $key => $value) {
            /**
             * We'll ignore all values that are empty()
             * to avoid checking them again but we need
             * to make sure they are not zero
             */
            if(empty($value) && $value !== 0)
                continue;

            $this->environment[$key] = $value;
        }
    }

    public function isEmpty() : bool
    {
        return empty($this->environment);
    }

    public function isSet(string $key) : bool
    {
        return array_key_exists($key, $this->environment);
    }

    public function getValue(string $key)
    {
        if($this->isSet($key))
            return $this->environment[$key];

        return null;
    }
}
