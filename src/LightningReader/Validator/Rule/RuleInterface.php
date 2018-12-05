<?php

namespace LightningReader\Validator\Rule;


interface RuleInterface
{
    public function test(string $data) : bool;
}
