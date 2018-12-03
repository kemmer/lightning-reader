<?php

namespace LightningReader\Validator;


interface ValidatorInterface
{
    public function createField();
    public function setFieldData();
    public function validate() : bool;
}
