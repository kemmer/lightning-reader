<?php

namespace LightningReader\Database\Information;


interface Table
{
    public static function fields() : array;    /* Must return a field list (without id field) */
}
