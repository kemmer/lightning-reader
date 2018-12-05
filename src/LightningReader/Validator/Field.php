<?php

namespace LightningReader\Validator;

use LightningReader\Validator\Rule\RuleInterface;


class Field
{
    private $data = "";
    private $rules = [];

    public function setData(string $data)
    {
        $this->data = $data;
    }

    public function addRule(RuleInterface $rule)
    {
        $this->rules [] = $rule;
    }

    public function check(): bool
    {
        if(empty($this->data))
            return false;

        foreach($this->rules as $rule) {
            if(! $rule->test($this->data))
                return false;
        }

        return true;
    }
}
