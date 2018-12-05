<?php

namespace LightningReader\Validator\Rule;

use LightningReader\Validator\Rule\RuleInterface;


class ServiceRule implements RuleInterface
{
    public function test(string $data) : bool
    {
        $pieces = explode("-", $data);

        if(count($pieces) == 2) {
            if(is_string($pieces[0]) && is_string($pieces[1]))
                return true;
        }

        return false;
    }
}
