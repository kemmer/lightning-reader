<?php

namespace LightningReader\Validator;


class RequestLogValidator
{
    public $pending = [];
    public $current = [];

    public function data($data)
    {
        $this->current['data'] = $data;
        return $this;
    }

    public function rule($rule)
    {
    }

    /* Moves the data from current to pending */
    public function set()
    {
        $this->pending [] = $this->current;
        $this->current = [];

        return $this;
    }

    public function validate() : bool
    {

    }
}
