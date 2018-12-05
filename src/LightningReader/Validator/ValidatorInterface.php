<?php

namespace LightningReader\Validator;

use LightningReader\Validator\Rule\RuleInterface;


interface ValidatorInterface
{
    public function newField(string $name);
    public function addFieldRule(string $name, RuleInterface $rule);
    public function setFieldData(string $name, string $data);
    public function validate() : bool;
}
