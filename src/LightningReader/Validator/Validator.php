<?php

namespace LightningReader\Validator;

use LightningReader\Validator\ValidatorInterface;
use LightningReader\Validator\Field;
use LightningReader\Validator\Rule\RuleInterface;


class Validator implements ValidatorInterface
{
    private $fields = [];

    public function newField(string $name)
    {
        $this->fields[$name] = new Field;
    }

    public function addFieldRule(string $name, RuleInterface $rule)
    {
        if(array_key_exists($name, $this->fields))
            $this->fields[$name]->addRule($rule);
    }

    public function setFieldData(string $name, string $data)
    {
        if(array_key_exists($name, $this->fields))
            $this->fields[$name]->setData($data);
    }

    public function validate() : bool
    {
        foreach ($this->fields as $field) {
            if(! $field->check())
                return false;
        }

        return true;
    }
}
