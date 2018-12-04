<?php

namespace LightningReader\Database\Operation;

use LightningReader\Database\DatabaseInterface;


/**
 * MultipleInsert
 *
 * Inserts multiple lines at once
 */
class MultipleInsert
{
    private $connection;

    public function __construct(DatabaseInterface $connection)
    {
        $this->connection = $connection;
        $this->connection->connect();
    }

    public function insert(string $table, array $columns, array $lines)
    {
        $query = "INSERT INTO ".$table." (";

        foreach($columns as $col) {
            $query .= $col;
            $query .= ",";
        }
        $query = rtrim($query, ",");

        $query .= ") VALUES ";

        foreach($lines as $line) {
            $query .= "(";
            foreach($line as $value) {
                $query .= "'".$value."'";
                $query .= ",";
            }
            $query = rtrim($query, ",");
            $query .= "),";
        }
        $query = rtrim($query, ",");

        // Insert the lines at once
        $this->connection->insert($query);
    }
}
