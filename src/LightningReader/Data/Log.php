<?php

namespace LightningReader\Data;

use LightningReader\Data\{CompletenessInterface, RegisterInterface};
use LightningReader\Validator\ValidatorInterface;


/**
 * Log
 *
 * A contract for a basic log entity
 */
abstract class Log implements CompletenessInterface, RegisterInterface, SerializeInterface
{
    protected $fields;      /* Fields that represent keys in $payload and usually reflect the DB */
    protected $payload;     /* Data hold by this log */
    protected $validator;   /* Validator object for checking the data */

    /* Flags */
    protected $completed;
    protected $validated;
    protected $sanitized;

    abstract protected function configureFields();
    abstract protected function configurePayload();
    abstract protected function configureValidatorRules();

    final public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;

        $this->configureFields();
        $this->configurePayload();
        $this->configureValidatorRules();
        $this->configureFlags();
    }

    private function configureFlags()
    {
        $this->completed = false;
        $this->validated = false;
        $this->sanitized = false;
    }

    public function __set(string $name, $value)
    {
        if(array_key_exists($name, $this->payload))
            $this->payload[$name] = $value;
    }

    public function __get(string $name)
    {
        if(array_key_exists($name, $this->payload))
            return $this->payload[$name];
        return null;
    }
}
