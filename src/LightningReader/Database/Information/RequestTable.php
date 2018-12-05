<?php

namespace LightningReader\Database\Information;

use LightningReader\Database\Information\TableInterface;
use LightningReader\Database\DatabaseInterface;


class RequestTable implements TableInterface
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

    public static function count(DatabaseInterface $connection, array $filters)
    {
        $query = "SELECT COUNT(*) as size FROM request WHERE 1=1 ";
        $parameters = [];

        if(array_key_exists("services", $filters) && !empty($filters["services"])) {
            $services = $filters["services"];
            if(! is_array($services))
                $services = [$services];

            $whereClause = " AND (";
            foreach($services as $service) {
                $whereClause .= "service = ? OR ";
                $parameters [] = $service;
            }
            $whereClause = rtrim($whereClause, "OR ");
            $whereClause .= ") ";

            $query .= $whereClause;
        }

        if(array_key_exists("statusCode", $filters) && !empty($filters["statusCode"])) {
            $http_code = $filters["statusCode"];
            $where = " AND (http_code = ?) ";
            $query .= $where;
            $parameters [] = $http_code;
        }

        if(array_key_exists("fromDateTime", $filters) && !empty($filters["fromDateTime"])) {
            $moment_from = $filters["fromDateTime"];
            $where = " AND (moment >= ?) ";
            $query .= $where;
            $parameters [] = $moment_from;
        }

        if(array_key_exists("toDateTime", $filters) && !empty($filters["toDateTime"])) {
            $moment_to = $filters["toDateTime"];
            $where = " AND (moment <= ?) ";
            $query .= $where;
            $parameters [] = $moment_to;
        }

        // Query the data
        $pdo = $connection->getPDO();
        $statement = $pdo->prepare($query);
        $statement->execute($parameters);
        $request = $statement->fetch();

        return json_encode(["counter" => $request["size"]]);
    }
}
