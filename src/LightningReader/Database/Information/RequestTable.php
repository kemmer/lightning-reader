<?php

namespace LightningReader\Database\Information;

use LightningReader\Database\Information\Table;


class RequestTable implements Table
{
    public static function fields() : array
    {
        $fields = self::dataFields();
        array_unshift($fields, "file_info_id");

        return $fields;
    }

    public static function dataFields()
    {
        return ['service', 'moment', 'details', 'http_code'];
    }
}
