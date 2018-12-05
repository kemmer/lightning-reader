<?php

namespace LightningReader\Validator\Rule;

use LightningReader\Validator\Rule\RuleInterface;


class NumericRule implements RuleInterface
{
    public function test(string $data) : bool
    {
        return is_numeric($data);
    }
}
