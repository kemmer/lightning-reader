<?php

namespace LightningReader\Database\Information;


interface TableInterface
{
    public static function fields() : array;    /* Must return a field list (without id field) */
}
